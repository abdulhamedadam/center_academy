<?php

namespace App\Filament\Resources\InstallmentPaymentResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InstallmentPaymentResource;
use App\Models\CourseInstallments;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstallmentPayments extends ListRecords
{
    protected static string $resource = InstallmentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => ListRecords\Tab::make(__('common.All'))
                ->icon('heroicon-o-list-bullet')
                ->badge(CourseInstallments::count())
                ->modifyQueryUsing(fn (Builder $query) => $query),

            'paid' => ListRecords\Tab::make(__('common.Paid'))
                ->icon('heroicon-o-check-circle')
                ->badge(CourseInstallments::where('status', 'paid')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid')),

            'unpaid' => ListRecords\Tab::make(__('common.Unpaid'))
                ->icon('heroicon-o-x-circle')
                ->badge(CourseInstallments::where('status', '!=','paid')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '!=','paid')),
        ];
    }


}
