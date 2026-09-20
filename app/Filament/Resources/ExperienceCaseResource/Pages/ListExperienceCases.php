<?php

namespace App\Filament\Resources\ExperienceCaseResource\Pages;

use App\Filament\Resources\ExperienceCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExperienceCases extends ListRecords
{
    protected static string $resource = ExperienceCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
