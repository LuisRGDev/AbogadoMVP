<?php

namespace App\Filament\Resources\PracticeAreaResource\Pages;

use App\Filament\Resources\PracticeAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPracticeAreas extends ListRecords
{
    protected static string $resource = PracticeAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
