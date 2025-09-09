<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Inventory: ') . $inventory->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.inventory.update', $inventory) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Product Info (Read-only) -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Product Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Name:</span> {{ $inventory->name }}
                                </div>
                                <div>
                                    <span class="font-medium">SKU:</span> {{ $inventory->sku }}
                                </div>
                                <div>
                                    <span class="font-medium">Current Price:</span> ${{ number_format($inventory->price, 2) }}
                                </div>
                                <div>
                                    <span class="font-medium">Category:</span> {{ $inventory->category?->name ?? 'Uncategorized' }}
                                </div>
                            </div>
                        </div>

                        <!-- Stock Management -->
                        <div>
                            <x-input-label for="stock" :value="__('Current Stock Level')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" min="0" name="stock" :value="old('stock', $inventory->stock)" required autofocus />
                            <p class="mt-1 text-sm text-gray-600">Current stock: {{ $inventory->stock }} units</p>
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="min_stock" :value="__('Minimum Stock Threshold')" />
                                <x-text-input id="min_stock" class="block mt-1 w-full" type="number" min="0" name="min_stock" :value="old('min_stock', $inventory->min_stock)" required />
                                <p class="mt-1 text-sm text-gray-600">Alert when stock falls below this level</p>
                                <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="max_stock" :value="__('Maximum Stock Level (Optional)')" />
                                <x-text-input id="max_stock" class="block mt-1 w-full" type="number" min="0" name="max_stock" :value="old('max_stock', $inventory->max_stock)" />
                                <p class="mt-1 text-sm text-gray-600">Maximum recommended stock level</p>
                                <x-input-error :messages="$errors->get('max_stock')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="supplier" :value="__('Supplier (Optional)')" />
                            <x-text-input id="supplier" class="block mt-1 w-full" type="text" name="supplier" :value="old('supplier', $inventory->supplier)" />
                            <p class="mt-1 text-sm text-gray-600">Primary supplier for this product</p>
                            <x-input-error :messages="$errors->get('supplier')" class="mt-2" />
                        </div>

                        <!-- Current Status Display -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Current Status</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-blue-700">Stock Status:</span>
                                    @if($inventory->stock <= 0)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($inventory->isLowStock())
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm text-blue-700">Inventory Value:</span>
                                    <span class="ml-2 font-medium text-blue-800">${{ number_format($inventory->stock * $inventory->price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Inventory') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>