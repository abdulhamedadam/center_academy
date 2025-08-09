<?php

namespace App\Filament\Resources\FollowUpResource\Pages;

use App\Filament\Resources\FollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFollowUps extends ManageRecords
{
    protected static string $resource = FollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
