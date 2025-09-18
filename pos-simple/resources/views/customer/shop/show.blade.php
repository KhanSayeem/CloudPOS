<x-customer-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $product->name }}</h2>
            <a href="{{ route('customer.shop.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Shop
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover rounded-lg">
                        @else
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                            </svg>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <p class="text-gray-600">{{ $product->description }}</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-3xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                            @if($product->category)
                                <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">SKU:</span>
                                    <span class="text-gray-600">{{ $product->sku }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Stock:</span>
                                    <span class="text-gray-600">{{ $product->stock }} available</span>
                                </div>
                                @if($product->barcode)
                                <div>
                                    <span class="font-medium text-gray-700">Barcode:</span>
                                    <span class="text-gray-600">{{ $product->barcode }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        @if($product->inStock())
                            <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="flex items-center space-x-4">
                                    <label for="quantity" class="font-medium text-gray-700">Quantity:</label>
                                    <input type="number"
                                           id="quantity"
                                           name="quantity"
                                           value="1"
                                           min="1"
                                           max="{{ $product->stock }}"
                                           class="w-20 border border-gray-300 rounded px-3 py-2 text-center">
                                </div>
                                <button type="submit"
                                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>