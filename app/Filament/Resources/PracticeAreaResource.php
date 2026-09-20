<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PracticeAreaResource\Pages;
use App\Models\PracticeArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PracticeAreaResource extends Resource
{
    protected static ?string $model = PracticeArea::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Áreas de práctica';

    protected static ?string $modelLabel = 'área de práctica';

    protected static ?string $pluralModelLabel = 'Áreas de práctica';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Presentación')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Nombre del área')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set, ?string $state) => $get('slug') ? null : $set('slug', Str::slug((string) $state))),
                            Forms\Components\TextInput::make('slug')->label('Dirección (URL)')->required()->alphaDash()->maxLength(255)->unique(ignoreRecord: true),
                            Forms\Components\Textarea::make('short')->label('Descripción corta (tarjeta)')->required()->rows(2)->maxLength(255)->columnSpanFull(),
                            Forms\Components\Textarea::make('overview')->label('Resumen del área')->required()->rows(5)->columnSpanFull(),
                        ])->columns(2),
                    Forms\Components\Section::make('Detalle')
                        ->schema([
                            Forms\Components\Repeater::make('matters')->label('Asuntos que atendemos')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir asunto')->default([]),
                            Forms\Components\Repeater::make('needs')->label('Necesidades típicas de los clientes')->simple(Forms\Components\TextInput::make('item')->required())->addActionLabel('Añadir necesidad')->default([]),
                            Forms\Components\Repeater::make('process')->label('Proceso')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Paso')->required(),
                                    Forms\Components\Textarea::make('description')->label('Descripción')->rows(2)->required(),
                                ])->columns(2)->addActionLabel('Añadir paso')->default([])->reorderable()->collapsible(),
                            Forms\Components\Repeater::make('faqs')->label('Preguntas frecuentes del área')
                                ->schema([
                                    Forms\Components\TextInput::make('question')->label('Pregunta')->required(),
                                    Forms\Components\Textarea::make('answer')->label('Respuesta')->rows(3)->required(),
                                ])->addActionLabel('Añadir pregunta')->default([])->reorderable()->collapsible(),
                        ]),
                    Forms\Components\Section::make('Posicionamiento en buscadores (SEO)')
                        ->schema([
                            Forms\Components\TextInput::make('seo_title')->label('Título SEO')->maxLength(255),
                            Forms\Components\Textarea::make('seo_description')->label('Descripción SEO')->rows(2)->maxLength(300),
                        ])->collapsed(),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make('Visibilidad')
                        ->schema([
                            Forms\Components\Toggle::make('is_active')->label('Visible en el sitio')->default(true),
                            Forms\Components\TextInput::make('num')->label('Número')->required()->maxLength(4)->default(fn (): string => str_pad((string) (PracticeArea::withTrashed()->count() + 1), 2, '0', STR_PAD_LEFT)),
                            Forms\Components\Select::make('icon')->label('Icono')->options([
                                'building' => 'Edificio (corporativo)', 'columns' => 'Columnas (litigio)', 'people' => 'Personas (laboral)',
                                'house' => 'Casa (inmobiliario)', 'document' => 'Documento (civil y mercantil)', 'mark' => 'Marca (propiedad intelectual)',
                                'scale' => 'Balanza', 'shield' => 'Escudo', 'briefcase' => 'Maletín', 'lock' => 'Candado', 'target' => 'Objetivo',
                            ])->native(false)->default('scale'),
                        ]),
                    Forms\Components\Section::make('Imagen de portada')
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                ->label('Imagen')
                                ->collection('cover')
                                ->image()
                                ->imageEditor()
                                ->maxSize(5120)
                                ->helperText('Horizontal 16:9, oscura y sin texto, hasta 5 MB. Sin imagen se usa la ilustración propia del área.'),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('num')->label('N.º')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Área')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('short')->label('Descripción')->limit(70)->wrap()->toggleable(),
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
            'index' => Pages\ListPracticeAreas::route('/'),
            'create' => Pages\CreatePracticeArea::route('/create'),
            'edit' => Pages\EditPracticeArea::route('/{record}/edit'),
        ];
    }
}
