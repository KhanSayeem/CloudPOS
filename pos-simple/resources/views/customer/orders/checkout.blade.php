<x-customer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('customer.orders.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Customer Details Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold mb-6">Customer Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name *
                                    </label>
                                    <input type="text" name="customer_name" id="customer_name" 
                                           value="{{ old('customer_name', auth()->user()->name) }}" 
                                           required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror">
                                    @error('customer_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address *
                                    </label>
                                    <input type="email" name="customer_email" id="customer_email" 
                                           value="{{ old('customer_email', auth()->user()->email) }}" 
                                           required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_email') border-red-500 @enderror">
                                    @error('customer_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="customer_phone" id="customer_phone" 
                                           value="{{ old('customer_phone') }}" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror">
                                    @error('customer_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Notes (Optional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="4" 
                                              placeholder="Any special instructions or notes for your order..."
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Review -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <h3 class="text-lg font-semibold mb-6">Order Review</h3>
                            
                            <div class="space-y-4">
                                @foreach($items as $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <!-- Product Image Placeholder -->
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover rounded">
                                            @else
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                            <p class="text-gray-600 text-sm">{{ $item->product->sku }}</p>
                                        </div>
                                        
                                        <!-- Quantity and Price -->
                                        <div class="text-right">
                                            <p class="font-semibold">{{ $item->qty }} × ${{ number_format($item->unit_price, 2) }}</p>
                                            <p class="text-green-600 font-semibold">${{ number_format($item->line_total, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary and Payment -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span>Items ({{ $count }}):</span>
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

                            <!-- Payment Method -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-semibold mb-2">Payment Method</h4>
                                <p class="text-sm text-gray-600">
                                    Pay at pickup/delivery. We accept cash, card, and digital payments.
                                </p>
                            </div>
                            
                            <!-- Place Order Button -->
                            <div class="mt-6 space-y-3">
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    Place Order
                                </button>
                                
                                <a href="{{ route('customer.cart.index') }}" 
                                   class="block w-full bg-gray-200 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-300 transition-colors">
                                    Back to Cart
                                </a>
                            </div>

                            <div class="mt-4 text-xs text-gray-500">
                                <p>By placing this order, you agree to our terms and conditions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-customer-layout>