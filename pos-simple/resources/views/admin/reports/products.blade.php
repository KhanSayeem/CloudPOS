<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Product Performance</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-4">
            <form method="GET" class="flex items-end gap-3 mb-4">
                <div>
                    <label class="text-sm">Period (days)</label>
                    <select name="period" class="border rounded p-2">
                        @foreach([7,14,30,60,90] as $p)
                            <option value="{{ $p }}" @selected((string)$p===(string)$period)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Apply</button>
            </form>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Product</th>
                        <th>SKU</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productStats as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->name }}</td>
                            <td>{{ $p->sku }}</td>
                            <td>{{ (int) $p->total_sold }}</td>
                            <td>${{ number_format($p->total_revenue, 2) }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>{{ $p->status ? 'Active' : 'Inactive' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $productStats->links() }}</div>
        </div>
    </div>
</x-app-layout>

