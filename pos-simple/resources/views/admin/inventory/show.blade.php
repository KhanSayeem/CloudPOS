<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Inventory Details: ') . $inventory->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.inventory.edit', $inventory) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Inventory
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Inventory
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
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">SKU</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->sku }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->category?->name ?? 'Uncategorized' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                                <p class="mt-1 text-lg font-semibold 
                                    @if($inventory->stock <= 0) text-red-600 
                                    @elseif($inventory->isLowStock()) text-yellow-600
                                    @else text-green-600 @endif">
                                    {{ $inventory->stock }} units
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock Status</label>
                                <div class="mt-1">
                                    @if($inventory->stock <= 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($inventory->isLowStock())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low Stock Alert
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Minimum Stock Level</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->min_stock }} units</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Maximum Stock Level</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->max_stock ?? 'Not set' }} units</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->supplier ?? 'Not specified' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($inventory->price, 2) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cost Price</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($inventory->cost_price ?? 0, 2) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Inventory Value</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($inventory->stock * $inventory->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Analysis -->
                    @if($inventory->cost_price)
                        <div class="mt-8 border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Profit Analysis</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Profit per Unit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($inventory->profit, 2) }}</dd>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($inventory->margin, 1) }}%</dd>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">Total Potential Profit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($inventory->stock * $inventory->profit, 2) }}</dd>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Product Details -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inventory->description ?? 'No description available' }}</p>
                            </div>
                            
                            @if($inventory->barcode)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barcode</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $inventory->barcode }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-500">{{ $inventory->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>