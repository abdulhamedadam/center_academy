<?php

namespace App\Filament\Resources\CrmLeadSourceResource\Pages;

use App\Filament\Resources\CrmLeadSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmLeadSources extends ListRecords
{
    protected static string $resource = CrmLeadSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 