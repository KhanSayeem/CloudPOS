<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sales</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded p-4">
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
                    @foreach($sales as $s)
                        <tr class="border-b">
                            <td class="py-2">{{ $s->id }}</td>
                            <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $s->user?->name }}</td>
                            <td>{{ number_format($s->total, 2) }}</td>
                            <td><a class="text-blue-600" href="{{ route('sales.show', $s) }}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
