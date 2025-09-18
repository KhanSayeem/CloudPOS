<x-customer-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Shop Products</h2>
            
            <!-- Search Form -->
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search products..." 
                       class="border border-gray-300 rounded px-3 py-2">
                <select name="category" class="border border-gray-300 rounded px-3 py-2 pr-8 appearance-none bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 4 5\'><path fill=\'%23666\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/></svg>'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Product Image Placeholder -->
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="h-full w-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                            </div>
                            
                            @if($product->category)
                                <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs mb-3">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            
                            <!-- Add to Cart Form -->
                            @if($product->inStock())
                                <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                           class="w-16 border border-gray-300 rounded px-2 py-1 text-center">
                                    <button type="submit" 
                                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                            
                            <!-- View Product Link -->
                            <a href="{{ route('customer.shop.show', $product) }}" 
                               class="block text-center text-blue-600 hover:text-blue-800 mt-2 text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No products found.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-customer-layout>