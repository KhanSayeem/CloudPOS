<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded p-6">
            <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 font-medium">SKU *</label>
                            <input class="w-full border p-2 rounded" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Barcode</label>
                            <input class="w-full border p-2 rounded" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                            @error('barcode')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Product Name *</label>
                        <input class="w-full border p-2 rounded" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Category</label>
                        <select name="category_id" class="w-full border p-2 rounded">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::active()->get() as $category)
                                <option value="{{ $category->id }}" {{ (int) old('category_id', $product->category_id) === $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Description</label>
                        <textarea class="w-full border p-2 rounded" rows="3" name="description">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-4">Pricing & Supplier</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 font-medium">Selling Price *</label>
                            <input class="w-full border p-2 rounded" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Cost Price</label>
                            <input class="w-full border p-2 rounded" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}">
                            @error('cost_price')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Supplier</label>
                        <input class="w-full border p-2 rounded" name="supplier" value="{{ old('supplier', $product->supplier) }}">
                        @error('supplier')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-4">Inventory Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-1 font-medium">Stock *</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Minimum Stock *</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required>
                            <small class="text-gray-500">Alert when stock falls below this level</small>
                            @error('min_stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Maximum Stock</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="max_stock" value="{{ old('max_stock', $product->max_stock) }}">
                            <small class="text-gray-500">Optional maximum stock level</small>
                            @error('max_stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="status" {{ old('status', $product->status) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium">Active Product</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <x-primary-button type="submit">{{ __('Update Product') }}</x-primary-button>
                    <a class="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300" href="{{ route('products.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
