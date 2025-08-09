<?php

namespace App\Filament\Resources\LeadsResource\Pages;

use App\Filament\Resources\LeadsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLeads extends ManageRecords
{
    protected static string $resource = LeadsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
