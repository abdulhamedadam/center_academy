<?php

namespace App\Filament\Resources\CrmLeadsStatusResource\Pages;

use App\Filament\Resources\CrmLeadsStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmLeadsStatuses extends ListRecords
{
    protected static string $resource = CrmLeadsStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 