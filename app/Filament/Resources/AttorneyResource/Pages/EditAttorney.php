<?php

namespace App\Filament\Resources\AttorneyResource\Pages;

use App\Filament\Resources\AttorneyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttorney extends EditRecord
{
    protected static string $resource = AttorneyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
