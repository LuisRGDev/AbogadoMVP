<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Insights (artículos)';

    protected static ?string $modelLabel = 'artículo';

    protected static ?string $pluralModelLabel = 'Insights (artículos)';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Contenido')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Título')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set, ?string $state) => $get('slug') ? null : $set('slug', Str::slug((string) $state))),
                            Forms\Components\TextInput::make('slug')
                                ->label('Dirección (URL)')
                                ->helperText('Se genera desde el título. Solo minúsculas, números y guiones.')
                                ->required()
                                ->alphaDash()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Forms\Components\Textarea::make('excerpt')
                                ->label('Extracto')
                                ->helperText('Aparece en las tarjetas y bajo el título. Máximo 255 caracteres.')
                                ->required()
                                ->rows(3)
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('body')
                                ->label('Cuerpo del artículo')
                                ->required()
                                ->toolbarButtons(['h2', 'h3', 'bold', 'italic', 'underline', 'strike', 'link', 'bulletList', 'orderedList', 'blockquote', 'undo', 'redo'])
                                ->helperText('Use títulos de nivel 2 (H2) para las secciones: se convierten en el índice del artículo.'),
                        ]),
                    Forms\Components\Section::make('Posicionamiento en buscadores (SEO)')
                        ->schema([
                            Forms\Components\TextInput::make('seo_title')->label('Título SEO')->maxLength(255)->helperText('Si se deja vacío se usa el título del artículo.'),
                            Forms\Components\Textarea::make('seo_description')->label('Descripción SEO')->rows(2)->maxLength(300)->helperText('Ideal: 120 a 160 caracteres.'),
                        ])->collapsed(),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make('Publicación')
                        ->schema([
                            Forms\Components\Toggle::make('is_published')->label('Publicado')->default(false),
                            Forms\Components\DatePicker::make('date')
                                ->label('Fecha de publicación')
                                ->helperText('Si es una fecha futura, el artículo se publicará ese día automáticamente.')
                                ->required()
                                ->native(false)
                                ->default(now()),
                            Forms\Components\TextInput::make('category')
                                ->label('Categoría')
                                ->required()
                                ->maxLength(80)
                                ->datalist(fn (): array => Article::query()->whereNotNull('category')->distinct()->pluck('category')->all()),
                            Forms\Components\TextInput::make('read_time')
                                ->label('Tiempo de lectura')
                                ->placeholder('Se calcula solo')
                                ->helperText('Opcional. Ej.: 6 min')
                                ->maxLength(30),
                        ]),
                    Forms\Components\Section::make('Imagen de portada')
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('featured')
                                ->label('Imagen')
                                ->collection('featured')
                                ->image()
                                ->imageEditor()
                                ->maxSize(4096)
                                ->helperText('Horizontal (3:2), hasta 4 MB. Sin imagen se usa una ilustración del despacho.'),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured')->label('')->collection('featured')->height(44)->defaultImageUrl(fn (Article $record): string => $record->coverUrl()),
                Tables\Columns\TextColumn::make('title')->label('Título')->searchable()->sortable()->wrap()->limit(70),
                Tables\Columns\TextColumn::make('category')->label('Categoría')->badge()->searchable(),
                Tables\Columns\TextColumn::make('date')->label('Fecha')->date('d/m/Y')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publicado')->boolean(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->label('Categoría')->options(fn (): array => Article::query()->distinct()->pluck('category', 'category')->all()),
                Tables\Filters\TernaryFilter::make('is_published')->label('Publicado'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_site')
                    ->label('Ver en el sitio')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Article $record): string => route('articles.show', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Article $record): bool => $record->is_published),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publicar')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Pasar a borrador')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
