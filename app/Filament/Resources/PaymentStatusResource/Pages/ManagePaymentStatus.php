<?php

namespace App\Filament\Resources\PaymentStatusResource\Pages;

use App\Filament\Resources\PaymentStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentStatus extends ManageRecords
{
    protected static string $resource = PaymentStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 