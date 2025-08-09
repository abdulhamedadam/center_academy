<?php

namespace App\Filament\Resources\ExpenseItemsResource\Pages;

use App\Filament\Resources\ExpenseItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExpenseItems extends ManageRecords
{
    protected static string $resource = ExpenseItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
