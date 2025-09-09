<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Transactions</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end mb-4">
                <div>
                    <label class="text-sm">Search</label>
                    <input name="search" value="{{ $search }}" class="border rounded p-2" placeholder="# or user">
                </div>
                <div>
                    <label class="text-sm">From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="border rounded p-2">
                </div>
                <div>
                    <label class="text-sm">To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="border rounded p-2">
                </div>
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Filter</button>
            </form>

            <div class="mb-4">
                <strong>Total:</strong> {{ $stats['total_transactions'] }}
                <span class="mx-2">|</span>
                <strong>Today:</strong> {{ $stats['today_transactions'] }}
                <span class="mx-2">|</span>
                <strong>Revenue:</strong> ${{ number_format($stats['total_revenue'], 2) }}
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">#</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                        <tr class="border-b">
                            <td class="py-2">{{ $t->id }}</td>
                            <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $t->user?->name }}</td>
                            <td>${{ number_format($t->total, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $t) }}" class="text-indigo-600">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $transactions->links() }}</div>
        </div>
    </div>
</x-app-layout>

