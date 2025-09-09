<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ __('Create Product') }}</h2></x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="post" action="{{ route('products.store') }}">
                @csrf
                
                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">SKU *</label>
                            <input class="w-full border p-2 rounded" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Barcode</label>
                            <input class="w-full border p-2 rounded" name="barcode" value="{{ old('barcode') }}">
                            @error('barcode')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Product Name *</label>
                        <input class="w-full border p-2 rounded" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Category</label>
                        <select name="category_id" class="w-full border p-2 rounded">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::active()->get() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Description</label>
                        <textarea class="w-full border p-2 rounded" rows="3" name="description">{{ old('description') }}</textarea>
                        @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Pricing & Supplier -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Pricing & Supplier</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Selling Price *</label>
                            <input class="w-full border p-2 rounded" type="number" step="0.01" min="0" name="price" value="{{ old('price', '0.00') }}" required>
                            @error('price')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Cost Price</label>
                            <input class="w-full border p-2 rounded" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price') }}">
                            @error('cost_price')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Supplier</label>
                        <input class="w-full border p-2 rounded" name="supplier" value="{{ old('supplier') }}">
                        @error('supplier')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Inventory Management -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Inventory Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Initial Stock *</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="stock" value="{{ old('stock', '0') }}" required>
                            @error('stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Minimum Stock *</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="min_stock" value="{{ old('min_stock', '5') }}" required>
                            <small class="text-gray-500">Alert when stock falls below this level</small>
                            @error('min_stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Maximum Stock</label>
                            <input class="w-full border p-2 rounded" type="number" min="0" name="max_stock" value="{{ old('max_stock') }}">
                            <small class="text-gray-500">Optional maximum stock level</small>
                            @error('max_stock')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="status" {{ old('status', true) ? 'checked' : '' }} class="mr-2"> 
                        <span class="font-medium">Active Product</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Product</button>
                    <a class="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300" href="{{ route('products.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
