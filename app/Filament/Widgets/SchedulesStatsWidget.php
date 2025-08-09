<?php

namespace App\Filament\Widgets;

use App\Models\CourseGroups;
use App\Models\CourseGroupDays;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SchedulesStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make(__('common.total_groups'), CourseGroups::count())
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->description(__('common.all_course_groups')),

            Card::make(__('common.active_schedules'), CourseGroupDays::count())
                ->icon('heroicon-o-calendar-days')
                ->color('success')
                ->description(__('common.scheduled_sessions')),

            Card::make(__('common.today_sessions'), $this->getTodaySessionsCount())
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->description(__('common.sessions_today')),

            Card::make(__('common.this_week_sessions'), $this->getThisWeekSessionsCount())
                ->icon('heroicon-o-calendar')
                ->color('info')
                ->description(__('common.sessions_this_week')),
        ];
    }

    protected function getTodaySessionsCount(): int
    {
        $today = now()->format('l');
        $dayMapping = [
            'Saturday' => 'saturday',
            'Sunday' => 'sunday',
            'Monday' => 'monday',
            'Tuesday' => 'tuesday',
            'Wednesday' => 'wednesday',
            'Thursday' => 'thursday',
            'Friday' => 'friday',
        ];

        $todayKey = $dayMapping[$today] ?? '';

        return CourseGroupDays::where('day', $todayKey)->count();
    }

    protected function getThisWeekSessionsCount(): int
    {
        return CourseGroupDays::count();
    }
} 