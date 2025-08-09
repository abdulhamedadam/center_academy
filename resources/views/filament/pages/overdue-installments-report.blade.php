<x-filament::page>
    {{ $this->form }}

    <div class="flex justify-end mt-4">
        <x-filament::button wire:click="generateReport" class="bg-primary-600 hover:bg-primary-700">
            {{ __('common.GenerateReport') }}
        </x-filament::button>
    </div>

    @if($this->showReport)
        <div class="p-6 bg-white rounded-lg shadow mt-6">
            <h2 class="text-xl font-bold mb-4 text-center text-primary-800">
                {{ __('common.OverdueInstallmentsReport') }}
            </h2>

            @if(count($this->reportData) > 0)
                <div class="overflow-x-auto w-full">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.Student') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.Course') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.DueDate') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.DaysOverdue') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.RemainingAmount') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.Status') }}</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ __('common.Contact') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->reportData as $installment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-center">{{ $installment['student'] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">{{ $installment['course'] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">{{ $installment['due_date'] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-semibold
                                    @if($installment['days_overdue'] > 30) text-red-600 @elseif($installment['days_overdue'] > 7) text-orange-500 @else text-yellow-600 @endif">
                                    {{ $installment['days_overdue'] }} {{ __('common.Days') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-bold
                                    @if($installment['remaining'] > 0) text-red-600 @else text-green-600 @endif">
                                    {{ number_format($installment['remaining'], 2) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($installment['status'] === 'paid') bg-green-100 text-green-800
                                        @elseif($installment['status'] === 'partial') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ __("common." . ucfirst($installment['status'])) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($installment['contact1'])
                                    <a href="https://wa.me/{{ $installment['contact1'] }}?text={{ urlencode(__('common.WhatsAppMessage', [
                                      'student' => $installment['student'],
                                       'course' => $installment['course'],
                                       'amount' => number_format($installment['remaining'], 2),
                                       'days' => $installment['days_overdue']
                                         ])) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors mx-1"
                                    >
                                        <x-heroicon-s-chat-bubble-bottom-center-text class="w-4 h-4 mr-1"/>
                                        <span class="hidden sm:inline">{{ __('common.WhatsApp') }}</span>
                                    </a>
                                @endif

                                @if($installment['contact2'])
                                    <a href="mailto:{{ $installment['contact2'] }}?subject={{ urlencode(__('common.PaymentReminderSubject')) }}&body={{ urlencode(__('common.EmailMessage', [
                                       'student' => $installment['student'],
                                       'course' => $installment['course'],
                                        'amount' => number_format($installment['remaining'], 2),
                                         'days' => $installment['days_overdue']
                                          ])) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors mx-1"
                                     >
                                        <x-heroicon-s-envelope class="w-4 h-4 mr-1"/>
                                        <span class="hidden sm:inline">{{ __('common.Email') }}</span>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    {{ __('common.NoOverdueInstallments') }}
                </div>
            @endif
        </div>
    @endif
</x-filament::page>
