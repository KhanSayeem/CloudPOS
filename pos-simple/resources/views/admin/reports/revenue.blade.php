<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Revenue Breakdown</h2>
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
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Apply</button>
            </form>

            <div class="mb-4">
                <strong>Total Revenue:</strong> ${{ number_format($summary['total_revenue'], 2) }}
                <span class="mx-2">|</span>
                <strong>Total Cost:</strong> ${{ number_format($summary['total_cost'], 2) }}
                <span class="mx-2">|</span>
                <strong>Total Profit:</strong> ${{ number_format($summary['total_profit'], 2) }}
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Product</th>
                        <th>SKU</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                        <th>Cost</th>
                        <th>Profit</th>
                        <th>Margin %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueData as $row)
                        <tr class="border-b">
                            <td class="py-2">{{ $row->name }}</td>
                            <td>{{ $row->sku }}</td>
                            <td>{{ (int) $row->total_sold }}</td>
                            <td>${{ number_format($row->total_revenue, 2) }}</td>
                            <td>${{ number_format($row->total_cost, 2) }}</td>
                            <td>${{ number_format($row->total_profit, 2) }}</td>
                            <td>{{ number_format($row->profit_margin, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $revenueData->links() }}</div>
        </div>
    </div>
</x-app-layout>

