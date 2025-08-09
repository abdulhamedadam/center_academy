<x-filament::page>
    @include('filament.resources.students-resource.pages.details')

    <div class="space-y-6">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button
                @class([
            'px-4 py-2 font-medium text-sm',
            'border-b-2 border-primary-500 text-primary-600' => $activeTab === 'attendance',
            'text-gray-500 hover:text-gray-700' => $activeTab !== 'attendance'
            ])
            wire:click="changeTab('attendance')"
            >
            {{ __('common.AllAttendance') }}
            </button>

            <button
                @class([
            'px-4 py-2 font-medium text-sm',
            'border-b-2 border-primary-500 text-primary-600' => $activeTab === 'absent',
            'text-gray-500 hover:text-gray-700' => $activeTab !== 'absent'
            ])
            wire:click="changeTab('absent')"
            >
            {{ __('common.AbsenceRecords') }}
            </button>

            <button
                @class([
            'px-4 py-2 font-medium text-sm',
            'border-b-2 border-primary-500 text-primary-600' => $activeTab === 'stats',
            'text-gray-500 hover:text-gray-700' => $activeTab !== 'stats'
            ])
            wire:click="changeTab('stats')"
            >
            {{ __('common.AttendanceStatistics') }}
            </button>
        </div>

        @if($activeTab !== 'stats')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                {{ $this->table }}
            </div>
        @else
            <div class="space-y-6">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Present Card -->
                    <x-filament::card class="relative overflow-hidden bg-gradient-to-br from-success-50 to-success-100 dark:from-gray-800 dark:to-gray-700 border-success-200 dark:border-gray-600">
                        <div class="absolute top-0 right-0 px-3 py-1 text-xs font-medium bg-success-500/20 text-success-700 dark:text-success-300 rounded-bl-lg">
                            {{ __('Attendance') }}
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold text-success-700 dark:text-success-400">{{ $this->getStats()['present'] }}</div>
                                    <div class="mt-1 text-sm font-medium text-success-600 dark:text-success-300">{{ __('Present') }}</div>
                                </div>
                                <div class="p-3 rounded-full bg-success-500/10 text-success-600 dark:text-success-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-success-600 dark:text-success-300 mb-1">
                                    <span>{{ __('Rate') }}</span>
                                    <span>{{ @$total > 0 ? round(($present/$total)*100, 1) : 0 }}%</span>
                                </div>
                                <div class="w-full bg-success-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-success-500 h-2 rounded-full" style="width: {{ @$total > 0 ? ($present/$total)*100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </x-filament::card>

                    <!-- Absent Card -->
                    <x-filament::card class="relative overflow-hidden bg-gradient-to-br from-danger-50 to-danger-100 dark:from-gray-800 dark:to-gray-700 border-danger-200 dark:border-gray-600">
                        <div class="absolute top-0 right-0 px-3 py-1 text-xs font-medium bg-danger-500/20 text-danger-700 dark:text-danger-300 rounded-bl-lg">
                            {{ __('Absence') }}
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold text-danger-700 dark:text-danger-400">{{ $this->getStats()['absent'] }}</div>
                                    <div class="mt-1 text-sm font-medium text-danger-600 dark:text-danger-300">{{ __('Absent') }}</div>
                                </div>
                                <div class="p-3 rounded-full bg-danger-500/10 text-danger-600 dark:text-danger-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-danger-600 dark:text-danger-300 mb-1">
                                    <span>{{ __('Rate') }}</span>
                                    <span>{{ @$total > 0 ? round(($absent/$total)*100, 1) : 0 }}%</span>
                                </div>
                                <div class="w-full bg-danger-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-danger-500 h-2 rounded-full" style="width: {{ @$total > 0 ? ($absent/$total)*100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </x-filament::card>

                    <!-- Late Card -->
                    <x-filament::card class="relative overflow-hidden bg-gradient-to-br from-warning-50 to-warning-100 dark:from-gray-800 dark:to-gray-700 border-warning-200 dark:border-gray-600">
                        <div class="absolute top-0 right-0 px-3 py-1 text-xs font-medium bg-warning-500/20 text-warning-700 dark:text-warning-300 rounded-bl-lg">
                            {{ __('Punctuality') }}
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold text-warning-700 dark:text-warning-400">{{ $this->getStats()['late'] }}</div>
                                    <div class="mt-1 text-sm font-medium text-warning-600 dark:text-warning-300">{{ __('Late Arrivals') }}</div>
                                </div>
                                <div class="p-3 rounded-full bg-warning-500/10 text-warning-600 dark:text-warning-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-warning-600 dark:text-warning-300 mb-1">
                                    <span>{{ __('Rate') }}</span>
                                    <span>{{ @$total > 0 ? round(($late/$total)*100, 1) : 0 }}%</span>
                                </div>
                                <div class="w-full bg-warning-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-warning-500 h-2 rounded-full" style="width: {{ @$total > 0 ? ($late/$total)*100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </x-filament::card>
                </div>

                <!-- Attendance Rate Card -->
                <x-filament::card class="border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Attendance Overview') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Total sessions:') }} {{ @$total }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Attendance Rate') }}</div>
                                    <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $this->getStats()['attendance_rate'] }}%</div>
                                </div>
                                <div class="p-3 rounded-lg bg-primary-500/10 text-primary-600 dark:text-primary-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Progress Bar -->
                            <div class="md:col-span-2">
                                <div class="relative pt-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-primary-600 bg-primary-100 dark:bg-gray-800 dark:text-primary-300">
                                    {{ __('Completion') }}
                                </span>
                                        </div>
                                        <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-300">
                                    {{ $present }}/{{ @$total }} {{ __('sessions') }}
                                </span>
                                        </div>
                                    </div>


                                    <div class="relative">
                                        <div class="flex h-3 overflow-hidden text-xs bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div style="width: {{ $this->getStats()['attendance_rate'] }}%;
                                                background: linear-gradient(to right, #3b82f6, #6366f1);
                                                box-shadow: none;
                                                transition: all 500ms ease-in-out"
                                                 class="flex flex-col justify-center text-center text-white whitespace-nowrap rounded-full">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-5 gap-2 mt-2 text-xs text-gray-600 dark:text-gray-400">
                                        <div>0%</div>
                                        <div class="text-center">25%</div>
                                        <div class="text-center">50%</div>
                                        <div class="text-center">75%</div>
                                        <div class="text-right">100%</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats Summary -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-3 rounded-lg bg-success-50 dark:bg-gray-800">
                                    <span class="block text-2xl font-bold text-success-600 dark:text-success-400">{{ $present }}</span>
                                    <span class="text-sm text-success-500 dark:text-success-400">{{ __('Present') }}</span>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-danger-50 dark:bg-gray-800">
                                    <span class="block text-2xl font-bold text-danger-600 dark:text-danger-400">{{ $absent }}</span>
                                    <span class="text-sm text-danger-500 dark:text-danger-400">{{ __('Absent') }}</span>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-warning-50 dark:bg-gray-800">
                                    <span class="block text-2xl font-bold text-warning-600 dark:text-warning-400">{{ $late }}</span>
                                    <span class="text-sm text-warning-500 dark:text-warning-400">{{ __('Late') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::card>
            </div>
        @endif
    </div>
</x-filament::page>
