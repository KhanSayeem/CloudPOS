<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sales Analytics</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end mb-4">
                <div>
                    <label class="text-sm">Start</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="border rounded p-2">
                </div>
                <div>
                    <label class="text-sm">End</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="border rounded p-2">
                </div>
                <div>
                    <label class="text-sm">Group By</label>
                    <select name="group_by" class="border rounded p-2">
                        <option value="day" @selected($groupBy==='day')>Day</option>
                        <option value="week" @selected($groupBy==='week')>Week</option>
                        <option value="month" @selected($groupBy==='month')>Month</option>
                        <option value="hour" @selected($groupBy==='hour')>Hour</option>
                    </select>
                </div>
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Apply</button>
            </form>

            <div class="mb-4">
                <strong>Total Sales:</strong> ${{ number_format($totals['total_sales'], 2) }}
                <span class="mx-2">|</span>
                <strong>Transactions:</strong> {{ $totals['total_transactions'] }}
                <span class="mx-2">|</span>
                <strong>Average:</strong> ${{ number_format($totals['average_transaction'], 2) }}
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Period</th>
                        <th>Total Sales</th>
                        <th>Transactions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData as $row)
                        <tr class="border-b">
                            <td class="py-2">{{ $row->period ?? ($row->date.' '.$row->period) }}</td>
                            <td>${{ number_format($row->total_sales, 2) }}</td>
                            <td>{{ $row->transaction_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

