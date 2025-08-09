<?php

namespace App\Filament\Widgets;

use App\Models\CrmFollowUps;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TodaysFollowUps extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder|Relation|null
    {
        return CrmFollowUps::query()
            ->whereDate('next_follow_up_date', today())
            ->whereNull('result')
            ->with(['lead', 'lead.assignee']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('lead.name')
                ->label('العميل')
                ->searchable(),

            TextColumn::make('lead.phone')
                ->label('الهاتف'),

            TextColumn::make('lead.assignee.name')
                ->label('مسؤول المتابعة'),

            TextColumn::make('follow_up_date')
                ->label('وقت المتابعة')
                ->dateTime('h:i A'),

            TextColumn::make('note')
                ->label('ملاحظات')
                ->limit(30),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('complete')
                ->label('إكمال') // Arabic: "Complete"
                ->icon('heroicon-o-check')
                ->form([
                    \Filament\Forms\Components\Select::make('result')
                        ->label('النتيجة') // Arabic: "Result"
                        ->options([
                            1 => 'مهتم',
                            2 => 'مشغول',
                            3 => 'لا يوجد رد',
                            4 => 'رقم خاطئ',
                            5 => 'غير مهتم',
                        ])
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('note')
                        ->label('ملاحظات')
                        ->required(),
                ])
                ->action(function (CrmFollowUps $record, array $data) {
                    $record->update([
                        'result' => $data['result'],
                        'note' => $data['note'],
                        'next_follow_up_date' => $data['result'] == 1
                            ? now()->addDays(3)
                            : now()->addDays(7),
                    ]);
                }),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'متابعات اليوم (' . CrmFollowUps::whereDate('follow_up_date', today())->count() . ')';
    }
}
