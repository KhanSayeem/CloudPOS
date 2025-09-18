<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Shopping Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($count > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Cart Items ({{ $count }})</h3>
                                
                                <div class="space-y-4">
                                    @foreach($items as $item)
                                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                            <!-- Product Image Placeholder -->
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-full h-full object-cover rounded">
                                                @else
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1">
                                                <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                                <p class="text-gray-600 text-sm">{{ $item->product->sku }}</p>
                                                <p class="text-green-600 font-semibold">${{ number_format($item->unit_price, 2) }}</p>
                                            </div>
                                            
                                            <!-- Quantity Update -->
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item->qty }}" 
                                                           min="0" max="{{ $item->product->stock }}" 
                                                           class="w-16 border border-gray-300 rounded px-2 py-1 text-center"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </div>
                                            
                                            <!-- Line Total -->
                                            <div class="text-right">
                                                <p class="font-semibold">${{ number_format($item->line_total, 2) }}</p>
                                            </div>
                                            
                                            <!-- Remove Button -->
                                            <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Clear Cart -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <form action="{{ route('customer.cart.clear') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to clear your cart?')"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            Clear Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Tax ({{ $tax_rate * 100 }}%):</span>
                                    <span>${{ number_format($tax_total, 2) }}</span>
                                </div>
                                
                                <hr class="border-gray-200">
                                
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Checkout Actions -->
                            <div class="mt-6 space-y-3">
                                @auth
                                    <a href="{{ route('customer.orders.checkout') }}" 
                                       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        Proceed to Checkout
                                    </a>
                                @else
                                    <p class="text-gray-600 text-sm text-center mb-3">Please login to checkout</p>
                                    <a href="{{ route('login') }}" 
                                       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        Login to Checkout
                                    </a>
                                @endauth
                                
                                <a href="{{ route('customer.shop.index') }}" 
                                   class="block w-full bg-gray-200 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-300 transition-colors">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v-2a4 4 0 118 0v2"></path>
                    </svg>
                    
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Add some products to get started!</p>
                    
                    <a href="{{ route('customer.shop.index') }}" 
                       class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-customer-layout>