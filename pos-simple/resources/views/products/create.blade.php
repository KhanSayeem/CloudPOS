<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ __('Create Product') }}</h2></x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded p-6">
            <form method="post" action="{{ route('products.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block mb-1">SKU</label>
                    <input class="w-full border p-2 rounded" name="sku" value="{{ old('sku') }}">
                    @error('sku')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Barcode</label>
                    <input class="w-full border p-2 rounded" name="barcode" value="{{ old('barcode') }}">
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Name</label>
                    <input class="w-full border p-2 rounded" name="name" value="{{ old('name') }}">
                    @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Description</label>
                    <textarea class="w-full border p-2 rounded" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Price</label>
                        <input class="w-full border p-2 rounded" type="number" step="0.01" name="price" value="{{ old('price',0) }}">
                        @error('price')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block mb-1">Stock</label>
                        <input class="w-full border p-2 rounded" type="number" name="stock" value="{{ old('stock',0) }}">
                        @error('stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>
                <label class="inline-flex items-center mb-4">
                    <input type="checkbox" name="status" {{ old('status', true) ? 'checked' : '' }} class="mr-2"> Active
                </label>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                    <a class="px-4 py-2 bg-gray-200 rounded" href="{{ route('products.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
