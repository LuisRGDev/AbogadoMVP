<?php

namespace App\Filament\Resources\AttorneyResource\Pages;

use App\Filament\Resources\AttorneyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttorneys extends ListRecords
{
    protected static string $resource = AttorneyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
