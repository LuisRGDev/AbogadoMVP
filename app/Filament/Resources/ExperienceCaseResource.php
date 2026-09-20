<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceCaseResource\Pages;
use App\Models\ExperienceCase;
use App\Models\PracticeArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExperienceCaseResource extends Resource
{
    protected static ?string $model = ExperienceCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Casos de experiencia';

    protected static ?string $modelLabel = 'caso';

    protected static ?string $pluralModelLabel = 'Casos de experiencia';

    protected static ?string $recordTitleAttribute = 'matter';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->description('Presente asuntos de forma anonimizada, sin promesas ni garantías de resultados y solo con autorización del cliente.')
                    ->schema([
                        Forms\Components\TextInput::make('matter')->label('Asunto')->required()->maxLength(255),
                        Forms\Components\Select::make('area')->label('Área de práctica')->required()->options(fn (): array => PracticeArea::query()->orderBy('sort_order')->pluck('title', 'title')->all())->native(false),
                        Forms\Components\Textarea::make('challenge')->label('Reto')->required()->rows(3)->columnSpanFull(),
                        Forms\Components\Textarea::make('strategy')->label('Estrategia')->required()->rows(3)->columnSpanFull(),
                        Forms\Components\Textarea::make('result')->label('Resultado')->required()->rows(3)->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')->label('Visible en el sitio')->default(true),
                        Forms\Components\Toggle::make('is_demo')->label('Caso de demostración')->helperText('Muestra la etiqueta de demostración. Desactívelo al capturar un caso real.')->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matter')->label('Asunto')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('area')->label('Área')->badge()->searchable(),
                Tables\Columns\IconColumn::make('is_demo')->label('Demo')->boolean()->trueIcon('heroicon-o-exclamation-triangle')->falseIcon('')->trueColor('warning'),
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
            'index' => Pages\ListExperienceCases::route('/'),
            'create' => Pages\CreateExperienceCase::route('/create'),
            'edit' => Pages\EditExperienceCase::route('/{record}/edit'),
        ];
    }
}
