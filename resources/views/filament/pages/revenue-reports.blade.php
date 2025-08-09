<x-filament::page>
    {{ $this->form }}

    @if($this->showReport)
        <div class="p-6 bg-white rounded-lg shadow mt-6">
            <h2 class="text-xl font-bold mb-4 text-center">{{ __('common.ReportResults') }}</h2>

            @if($this->data['report_type'] === 'monthly')
                <div class="grid gap-6 mt-6">

                    <!-- Data Table -->
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h2 class="text-xl font-bold mb-4 text-center">{{ __('common.ReportResults') }}</h2>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center">{{ __('common.Month') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.TotalRevenue') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.RevenueKPI') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.Paid') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.Remaining') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.DueInstallments') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.Students') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('common.Courses') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($this->reportData as $row)
                                    <tr>
                                        <td class="px-6 py-4 text-center">{{ @$row['month'] }}</td>
                                        <td class="px-6 py-4 text-center">{{ number_format(@$row['revenue'], 2) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if(!is_null(@$row['revenue_kpi']))
                                                <span class="{{ @$row['revenue_kpi'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                                {{ number_format(@$row['revenue_kpi'], 2) }}%
                                            </span>
                                            @else
                                                {{ __('common.N/A') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ number_format($row['paid'], 2) }}</td>
                                        <td class="px-6 py-4 text-center">{{ number_format($row['remaining'], 2) }}</td>
                                        <td class="px-6 py-4 text-center">{{ number_format($row['due_installments'], 2) }}</td>
                                        <td class="px-6 py-4 text-center">{{ $row['students'] }}</td>
                                        <td class="px-6 py-4 text-center">{{ $row['courses'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto w-full">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center">{{ __('common.Name') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('common.TotalRevenue') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('common.Paid') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('common.Remaining') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('common.DueInstallments') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('common.Students') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->reportData as $row)
                            <tr>
                                <td class="px-6 py-4 text-center">{{ $row['name'] }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($row['revenue'], 2) }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($row['paid'], 2) }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($row['remaining'], 2) }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($row['due_installments'], 2) }}</td>
                                <td class="px-6 py-4 text-center">{{ $row['students'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</x-filament::page>
