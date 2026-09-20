<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Preguntas frecuentes';

    protected static ?string $modelLabel = 'pregunta frecuente';

    protected static ?string $pluralModelLabel = 'Preguntas frecuentes';

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->description('Estas preguntas aparecen en «Nosotros» y en «Contacto». Las preguntas propias de cada área se editan dentro del área de práctica.')
                    ->schema([
                        Forms\Components\TextInput::make('question')->label('Pregunta')->required()->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('answer')->label('Respuesta')->required()->rows(4)->columnSpanFull(),
                        Forms\Components\Hidden::make('group')->default('general'),
                        Forms\Components\Toggle::make('is_active')->label('Visible en el sitio')->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->label('Pregunta')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('answer')->label('Respuesta')->limit(80)->wrap()->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Visible'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
