<?php

namespace App\Filament\Resources\CrmCommunicationTypeResource\Pages;

use App\Filament\Resources\CrmCommunicationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCommunicationTypes extends ManageRecords
{
    protected static string $resource = CrmCommunicationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 