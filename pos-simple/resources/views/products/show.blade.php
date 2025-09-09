<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details: ') . $product->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Product
                </a>
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">SKU</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $product->sku }}</p>
                            </div>

                            @if($product->barcode)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barcode</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $product->barcode }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->description ?? 'No description available' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    @if($product->status)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selling Price</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($product->price, 2) }}</p>
                            </div>

                            @if($product->cost_price)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cost Price</label>
                                    <p class="mt-1 text-sm text-gray-900">${{ number_format($product->cost_price, 2) }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                                <p class="mt-1 text-lg font-semibold 
                                    @if($product->stock <= 0) text-red-600 
                                    @elseif($product->isLowStock()) text-yellow-600
                                    @else text-green-600 @endif">
                                    {{ $product->stock }} units
                                </p>
                                @if($product->stock <= 0)
                                    <p class="text-sm text-red-600">Out of stock</p>
                                @elseif($product->isLowStock())
                                    <p class="text-sm text-yellow-600">Low stock alert</p>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Min Stock</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->min_stock }} units</p>
                                </div>
                                @if($product->max_stock)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Max Stock</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $product->max_stock }} units</p>
                                    </div>
                                @endif
                            </div>

                            @if($product->supplier)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->supplier }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Inventory Value</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($product->stock * $product->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Analysis -->
                    @if($product->cost_price)
                        <div class="mt-8 border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Profit Analysis</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Profit per Unit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($product->profit, 2) }}</dd>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($product->margin, 1) }}%</dd>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Total Potential Profit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($product->stock * $product->profit, 2) }}</dd>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product History</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>