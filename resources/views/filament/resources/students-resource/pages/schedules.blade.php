<x-filament-panels::page>

    @include('filament.resources.students-resource.pages.details')
    <x-filament::card>
        <div class="space-y-6" dir="rtl">
            <h3 class="text-lg font-medium">{{ __('Student Course Schedule') }}</h3>

            <div class="overflow-x-auto">
                <!-- Calendar Grid -->
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                    <!-- Months Header -->
                    <thead>
                    <tr>
                        <th class="w-24 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900"></th>
                        @foreach($this->months as $monthName => $monthData)
                            <th colspan="{{ count($monthData['weeks']) }}"
                                class="text-center font-medium py-2 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                {{ 'شهر - ' . $monthName }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>

                    <!-- Weeks Header -->
                    <thead>
                    <tr>
                        <th class="w-24 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800"></th>
                        @foreach($this->months as $monthData)
                            @foreach($monthData['weeks'] as $weekName)
                                <th class="w-30 text-center py-1 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    {{ $weekName }}
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                    </thead>

                    <!-- Days Rows -->
                    <tbody class="bg-white dark:bg-gray-800">
                    @foreach(['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'] as $dayName)
                        <tr>
                            <!-- Day Name Column -->
                            <td class="w-24 p-2 font-medium border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                {{ $dayName }}
                            </td>

                            <!-- Week Columns -->
                            @foreach($this->calendarData as $weekKey => $week)
                                @php
                                    $day = collect($week['days'])->firstWhere('dayName', $dayName);
                                    $isPast = $day && $day['date']->isPast() && !$day['date']->isToday();
                                    $isUpcoming = $day && $day['date']->isFuture() && $day['date']->diffInDays(now()) <= 7;
                                @endphp

                                <td class="w-30 p-2 border border-gray-200 dark:border-gray-700
                                        @if($day && $day['date']->isToday()) bg-primary-50 dark:bg-gray-600 @endif
                                @if($isPast) is-past @endif
                                @if($isUpcoming) is-upcomming @endif
                                @if(!$day) bg-gray-50 dark:bg-gray-800 @endif"
                                    style="min-width: 120px; height: 120px">

                                    <!-- In your Blade template (updated schedule card display) -->
                                    @if($day)
                                        <div >
                                            {{ $day['date']->format('Y-m-d') }}
                                        </div>

                                        <div >
                                            @foreach($day['schedules'] as $schedule)
                                                @php
                                                    $attendance = $this->getAttendanceStatus(
                                                        $schedule->course_id,
                                                        $day['date']->format('Y-m-d'),
                                                        $this->record
                                                    );

                                                    $cardStyle = "";


                                                @endphp

                                                <div style="{{ $cardStyle }}">
                                                    <div style="font-weight: 500;">{{ $schedule->course->name }}</div>
                                                    <div>{{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                                    <div style="color: #6b7280;">{{ @$schedule->instructor->name }}</div>
                                                    <div style="font-size: 0.75rem; margin-top: 0.25rem;">
                                                        @if($attendance['status'] == '1')
                                                            <span style="color: #16a34a;">● حاضر</span>
                                                        @elseif($attendance['status'] == '2')
                                                            <span style="color: #dc2626;">● غائب</span>
                                                        @elseif($attendance['status'] == '3')
                                                            <span style="color: #ca8a04;">● متأخر</span>
                                                        @else
                                                            <span style="color: #4b5563;">● لم يتم التسجيل</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::card>
</x-filament-panels::page>
