<?php

namespace App\Filament\Widgets;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestLeads extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Últimas solicitudes';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Contact::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge()->formatStateUsing(fn (ContactType $state): string => $state->label())->color(fn (ContactType $state): string => $state->color()),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->description(fn (Contact $record): string => $record->email)->weight(fn (Contact $record): ?string => $record->read_at ? null : 'bold'),
                Tables\Columns\TextColumn::make('area')->label('Área')->placeholder('—'),
                Tables\Columns\TextColumn::make('preferred_date')->label('Cita para')->date('d/m/Y')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->label('Estado')->badge()->formatStateUsing(fn (ContactStatus $state): string => $state->label())->color(fn (ContactStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('created_at')->label('Recibida')->since(),
            ])
            ->recordUrl(fn (Contact $record): string => ContactResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('Aún no hay solicitudes')
            ->emptyStateDescription('Cuando alguien use el formulario de contacto, aparecerá aquí.');
    }
}
