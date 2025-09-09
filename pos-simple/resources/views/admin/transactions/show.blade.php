<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Transaction #{{ $transaction->id }}</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-4">
            <p class="mb-2">
                <strong>Cashier:</strong> {{ $transaction->user?->name }}
                <span class="mx-2">|</span>
                <strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}
            </p>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Product</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $i)
                        <tr class="border-b">
                            <td class="py-2">{{ $i->product?->name }}</td>
                            <td>{{ $i->qty }}</td>
                            <td>{{ number_format($i->unit_price, 2) }}</td>
                            <td>{{ number_format($i->discount, 2) }}</td>
                            <td>{{ number_format($i->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <p><strong>Subtotal:</strong> {{ number_format($transaction->subtotal, 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($transaction->tax_total, 2) }}</p>
                <p class="text-lg"><strong>Total:</strong> {{ number_format($transaction->total, 2) }}</p>
            </div>

            <div class="mt-4">
                <a class="px-3 py-2 bg-gray-200 rounded" href="{{ route('admin.transactions.index') }}">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>

