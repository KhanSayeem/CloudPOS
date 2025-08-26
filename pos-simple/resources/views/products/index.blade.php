<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-100 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form class="mb-4" method="get">
            <input class="border p-2 rounded" type="text" name="q" value="{{ $q }}" placeholder="Search...">
            <button class="px-3 py-2 bg-blue-600 text-white rounded" type="submit">Search</button>
            <a class="ml-2 px-3 py-2 bg-gray-200 rounded" href="{{ route('products.create') }}">+ New</a>
        </form>

        <div class="bg-white shadow-sm rounded p-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">SKU</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($products as $p)
                    <tr class="border-b">
                        <td class="py-2">{{ $p->sku }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ number_format($p->price,2) }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-right">
                            <a class="text-blue-600 mr-2" href="{{ route('products.edit', $p) }}">Edit</a>
                            <form action="{{ route('products.destroy', $p) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="py-3" colspan="6">No products found.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
