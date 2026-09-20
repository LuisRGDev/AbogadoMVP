<?php

namespace App\Filament\Resources;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Solicitudes';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Solicitudes y citas';

    protected static ?string $modelLabel = 'solicitud';

    protected static ?string $pluralModelLabel = 'Solicitudes y citas';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = Contact::pending()->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Seguimiento')
                    ->schema([
                        Forms\Components\Select::make('status')->label('Estado')->options(ContactStatus::options())->required()->native(false),
                        Forms\Components\Textarea::make('notes')->label('Notas internas')->rows(5)->placeholder('Notas sobre esta solicitud (no visibles para el cliente)')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Solicitante')
                    ->schema([
                        Infolists\Components\TextEntry::make('type')->label('Tipo')->badge()->formatStateUsing(fn (ContactType $state): string => $state->label())->color(fn (ContactType $state): string => $state->color()),
                        Infolists\Components\TextEntry::make('name')->label('Nombre')->weight('bold'),
                        Infolists\Components\TextEntry::make('email')->label('Correo')->copyable()->url(fn (Contact $record): string => 'mailto:'.$record->email),
                        Infolists\Components\TextEntry::make('phone')->label('Teléfono')->placeholder('—')->copyable(),
                        Infolists\Components\TextEntry::make('area')->label('Área de interés')->placeholder('—'),
                        Infolists\Components\TextEntry::make('created_at')->label('Recibida')->dateTime('d/m/Y H:i')->since(),
                    ])->columns(3),

                Infolists\Components\Section::make('Cita solicitada')
                    ->visible(fn (Contact $record): bool => $record->type === ContactType::Appointment)
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_date')->label('Fecha')->date('l d \d\e F \d\e Y'),
                        Infolists\Components\TextEntry::make('preferred_slot')->label('Horario')->formatStateUsing(fn (Contact $record): ?string => $record->slotLabel()),
                        Infolists\Components\TextEntry::make('meeting_mode')->label('Modalidad')->formatStateUsing(fn (Contact $record): ?string => $record->modeLabel()),
                    ])->columns(3),

                Infolists\Components\Section::make('Mensaje')
                    ->schema([
                        Infolists\Components\TextEntry::make('message')->hiddenLabel()->columnSpanFull()->prose(),
                    ]),

                Infolists\Components\Section::make('Seguimiento')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')->label('Estado')->badge()->formatStateUsing(fn (ContactStatus $state): string => $state->label())->color(fn (ContactStatus $state): string => $state->color()),
                        Infolists\Components\TextEntry::make('notes')->label('Notas internas')->placeholder('Sin notas')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge()->formatStateUsing(fn (ContactType $state): string => $state->label())->color(fn (ContactType $state): string => $state->color()),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->description(fn (Contact $record): string => $record->email)->weight(fn (Contact $record): ?string => $record->read_at ? null : 'bold'),
                Tables\Columns\TextColumn::make('area')->label('Área')->searchable()->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('preferred_date')->label('Cita para')->date('d/m/Y')->placeholder('—')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Estado')->badge()->formatStateUsing(fn (ContactStatus $state): string => $state->label())->color(fn (ContactStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('created_at')->label('Recibida')->since()->sortable()->tooltip(fn (Contact $record): string => $record->created_at->format('d/m/Y H:i')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Estado')->options(ContactStatus::options()),
                Tables\Filters\SelectFilter::make('type')->label('Tipo')->options(collect(ContactType::cases())->mapWithKeys(fn (ContactType $type): array => [$type->value => $type->label()])->all()),
                Tables\Filters\Filter::make('upcoming')->label('Citas próximas')->query(fn (Builder $query): Builder => $query->appointments()->whereDate('preferred_date', '>=', today())->where('status', '!=', ContactStatus::Closed)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_contacted')
                    ->label('Contactado')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Contact $record): bool => $record->status === ContactStatus::Pending)
                    ->action(fn (Contact $record) => $record->update(['status' => ContactStatus::Contacted, 'read_at' => $record->read_at ?? now()])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_contacted')->label('Marcar como contactadas')->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['status' => ContactStatus::Contacted]))->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_closed')->label('Cerrar')->icon('heroicon-o-lock-closed')
                        ->action(fn (Collection $records) => $records->each->update(['status' => ContactStatus::Closed]))->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')->label('Exportar a CSV')->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => static::exportCsv($records))->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Aún no hay solicitudes')
            ->emptyStateDescription('Cuando alguien use el formulario de contacto o agende una cita, aparecerá aquí.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    /**
     * @param  Collection<int, Contact>  $records
     */
    public static function exportCsv(Collection $records): StreamedResponse
    {
        $rows = $records->map(fn (Contact $contact): array => array_map(
            static fn (mixed $value): mixed => is_string($value) && preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value,
            [
                $contact->created_at->format('Y-m-d H:i'), $contact->type->label(), $contact->name, $contact->email, $contact->phone,
                $contact->area, $contact->preferred_date?->toDateString(), $contact->slotLabel(), $contact->modeLabel(),
                $contact->status->label(), $contact->message, $contact->notes,
            ]
        ));

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Recibida', 'Tipo', 'Nombre', 'Correo', 'Teléfono', 'Área', 'Fecha de cita', 'Horario', 'Modalidad', 'Estado', 'Mensaje', 'Notas']);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 'solicitudes-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
