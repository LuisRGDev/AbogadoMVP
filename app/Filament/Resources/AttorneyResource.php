<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttorneyResource\Pages;
use App\Models\Attorney;
use App\Models\PracticeArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AttorneyResource extends Resource
{
    protected static ?string $model = Attorney::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Equipo (abogados)';

    protected static ?string $modelLabel = 'abogado';

    protected static ?string $pluralModelLabel = 'Equipo (abogados)';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Datos principales')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nombre completo')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set, ?string $state) => $get('slug') ? null : $set('slug', Str::slug((string) $state))),
                            Forms\Components\TextInput::make('slug')->label('Dirección (URL)')->required()->alphaDash()->maxLength(255)->unique(ignoreRecord: true),
                            Forms\Components\TextInput::make('position')->label('Cargo')->required()->maxLength(255)->placeholder('Socio(a) Director(a)'),
                            Forms\Components\TextInput::make('credentials')->label('Cédula profesional / credenciales')->maxLength(255),
                            Forms\Components\TextInput::make('linkedin')->label('LinkedIn (enlace completo)')->url()->maxLength(255)->columnSpanFull(),
                            Forms\Components\Textarea::make('bio_short')->label('Biografía breve')->helperText('Aparece en la tarjeta del equipo.')->rows(3)->columnSpanFull(),
                        ])->columns(2),
                    Forms\Components\Section::make('Trayectoria')
                        ->schema([
                            Forms\Components\Repeater::make('bio')->label('Biografía completa (párrafos)')->simple(Forms\Components\Textarea::make('paragraph')->required()->rows(3))->addActionLabel('Añadir párrafo')->reorderable(),
                            Forms\Components\Repeater::make('education')->label('Formación')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir')->default([]),
                            Forms\Components\Repeater::make('experience')->label('Experiencia profesional')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir')->default([]),
                            Forms\Components\Repeater::make('memberships')->label('Membresías')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir')->default([]),
                            Forms\Components\Repeater::make('languages')->label('Idiomas')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir')->default([]),
                        ]),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make('Fotografía')
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                                ->label('Retrato')
                                ->collection('avatar')
                                ->image()
                                ->imageEditor()
                                ->imageCropAspectRatio('4:5')
                                ->maxSize(4096)
                                ->helperText('Vertical (4:5), hasta 4 MB. Sin foto se usa una ilustración de marcador.'),
                        ]),
                    Forms\Components\Section::make('Visibilidad')
                        ->schema([
                            Forms\Components\Toggle::make('is_active')->label('Visible en el sitio')->default(true),
                            Forms\Components\Toggle::make('is_demo')->label('Perfil de demostración')->helperText('Muestra la etiqueta «Demo». Desactívelo al capturar datos reales.')->default(false),
                            Forms\Components\Select::make('areas')
                                ->label('Áreas de práctica')
                                ->multiple()
                                ->options(fn (): array => PracticeArea::query()->orderBy('sort_order')->pluck('title', 'title')->all())
                                ->preload(),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')->label('')->collection('avatar')->circular()->defaultImageUrl(fn (Attorney $record): string => $record->photoUrl()),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position')->label('Cargo')->searchable(),
                Tables\Columns\IconColumn::make('is_demo')->label('Demo')->boolean()->trueIcon('heroicon-o-exclamation-triangle')->falseIcon('')->trueColor('warning'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Visible'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Visible')])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttorneys::route('/'),
            'create' => Pages\CreateAttorney::route('/create'),
            'edit' => Pages\EditAttorney::route('/{record}/edit'),
        ];
    }
}
