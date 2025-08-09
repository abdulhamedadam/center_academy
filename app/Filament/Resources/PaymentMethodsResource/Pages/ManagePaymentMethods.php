<?php

namespace App\Filament\Resources\PaymentMethodsResource\Pages;

use App\Filament\Resources\PaymentMethodsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentMethods extends ManageRecords
{
    protected static string $resource = PaymentMethodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
