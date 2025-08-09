<x-filament::page>
    {{ $this->form }}

    @if($this->showReport)
        <div class="p-6 bg-white rounded-lg shadow mt-6">
            <h2 class="text-xl font-bold mb-4 text-center">{{ __('common.Payments Received Report') }}</h2>

            @if(count($this->reportData) > 0)
                <div class="overflow-x-auto w-full">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3  text-center">{{ __('common.Date') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Reference') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Student') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Course') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Amount') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Paid') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Method') }}</th>
                            <th class="px-6 py-3  text-center">{{ __('common.Status') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->reportData as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment['date'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment['reference'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment['student'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment['course'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap  text-center">{{ number_format($payment['amount'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap  text-center">{{ number_format($payment['paid'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment['method'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $payment['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($payment['status']) }}
                                        </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    {{ __('common.No payments found for the selected filters.') }}
                </div>
            @endif
        </div>
    @endif
</x-filament::page>
