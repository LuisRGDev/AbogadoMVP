<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Páginas y textos legales';

    protected static ?string $modelLabel = 'página';

    protected static ?string $pluralModelLabel = 'Páginas y textos legales';

    protected static ?string $recordTitleAttribute = 'title';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Encabezado')
                    ->schema([
                        Forms\Components\TextInput::make('slug')->label('Dirección (URL)')->disabled()->dehydrated(false),
                        Forms\Components\TextInput::make('eyebrow')->label('Etiqueta superior')->maxLength(255),
                        Forms\Components\TextInput::make('title')->label('Título')->required()->maxLength(255)->helperText('Puede resaltar palabras con asteriscos: *así*.')->columnSpanFull(),
                        Forms\Components\Textarea::make('text')->label('Texto introductorio')->rows(2)->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Section::make('Contenido')
                    ->description('Los textos legales deben ser redactados o revisados por el despacho antes de publicarse. Mientras existan corchetes [así], el sitio muestra un aviso de «texto de ejemplo».')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Contenido')
                            ->toolbarButtons(['h2', 'h3', 'bold', 'italic', 'underline', 'link', 'bulletList', 'orderedList', 'blockquote', 'undo', 'redo'])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('SEO y visibilidad')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')->label('Título SEO')->maxLength(255),
                        Forms\Components\Textarea::make('seo_description')->label('Descripción SEO')->rows(2)->maxLength(300),
                        Forms\Components\Toggle::make('is_active')->label('Página activa')->default(true),
                    ])->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Página')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('URL')->formatStateUsing(fn (string $state): string => '/'.$state)->color('gray'),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizada')->since(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Activa'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
