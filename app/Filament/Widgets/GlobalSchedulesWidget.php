<?php

namespace App\Filament\Widgets;

use App\Models\CourseGroups;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class GlobalSchedulesWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder|Relation|null
    {
        return CourseGroups::query()
            ->with(['groupDays', 'course', 'instructor'])
            ->select('tbl_course_groups.*')
            ->join('tbl_courses', 'tbl_course_groups.course_id', '=', 'tbl_courses.id')
            ->orderBy('tbl_courses.name');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('common.group'))
                ->searchable()
                ->sortable(),
            ...$this->getDayColumns(),
        ];
    }

    protected function getDayColumns(): array
    {
        $days = [
            'saturday' => __('common.saturday'),
            'sunday' => __('common.sunday'),
            'monday' => __('common.monday'),
            'tuesday' => __('common.tuesday'),
            'wednesday' => __('common.wednesday'),
            'thursday' => __('common.thursday'),
            'friday' => __('common.friday'),
        ];

        $columns = [];

        foreach ($days as $dayKey => $dayLabel) {
            $columns[] = TextColumn::make($dayKey)
                ->label($dayLabel)
                ->getStateUsing(function ($record) use ($dayKey) {
                    $groupDay = $record->groupDays->firstWhere('day', $dayKey);
                    if ($groupDay) {
                        $start = $groupDay->start_time ? date('H:i', strtotime($groupDay->start_time)) : '';
                        $end = $groupDay->end_time ? date('H:i', strtotime($groupDay->end_time)) : '';
                        $instructor = $record->instructor->name ?? '';
                        $groupName = $record->name ?? '';

                        return "<div style='line-height: 1.5;'>
                            <div style='color: #00bb00'>✓</div>
                            <div>{$start} - {$end}</div>
                            <div>{$groupName}</div>
                            <div>{$instructor}</div>
                        </div>";
                    }
                    return '';
                })
                ->html()
                ->wrap()
                ->alignCenter();
        }

        return $columns;
    }

    protected function getTableHeading(): string
    {
        return __('common.all_schedules') . ' (' . CourseGroups::count() . ')';
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view_all')
                ->label(__('common.view_all'))
                ->icon('heroicon-o-eye')
                ->url('/admin/global-schedules')
                ->openUrlInNewTab(),
        ];
    }
} 