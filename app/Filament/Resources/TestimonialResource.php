<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\PracticeArea;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Testimonios';

    protected static ?string $modelLabel = 'testimonio';

    protected static ?string $pluralModelLabel = 'Testimonios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Textarea::make('quote')->label('Testimonio')->required()->rows(4)->columnSpanFull(),
                        Forms\Components\TextInput::make('author')->label('Autor(a)')->required()->maxLength(255)->helperText('Use solo testimonios autorizados. Puede publicarse de forma anónima (ej. «Director de empresa»).'),
                        Forms\Components\Select::make('area')->label('Área relacionada')->options(fn (): array => PracticeArea::query()->orderBy('sort_order')->pluck('title', 'title')->all())->native(false),
                        Forms\Components\Toggle::make('is_active')->label('Visible en el sitio')->default(true),
                        Forms\Components\Toggle::make('is_demo')->label('Testimonio de demostración')->helperText('Muestra el aviso de demostración. Desactívelo con testimonios reales.')->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote')->label('Testimonio')->limit(80)->wrap()->searchable(),
                Tables\Columns\TextColumn::make('author')->label('Autor(a)')->searchable(),
                Tables\Columns\TextColumn::make('area')->label('Área')->badge(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
