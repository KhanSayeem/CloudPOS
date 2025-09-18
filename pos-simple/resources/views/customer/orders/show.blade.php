<x-customer-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Order {{ $order->order_number }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('customer.orders.index') }}" 
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                    Back to Orders
                </a>
                @if($order->canBeCancelled())
                    <form action="{{ route('customer.orders.cancel', $order) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Order Number</p>
                                <p class="text-lg font-semibold">{{ $order->order_number }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-600">Order Date</p>
                                <p class="text-lg">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-600">Status</p>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                       ($order->status === 'ready' ? 'bg-purple-100 text-purple-800' :
                                       'bg-yellow-100 text-yellow-800'))) }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Items</p>
                                <p class="text-lg">{{ $order->items->sum('qty') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Name</p>
                                <p class="text-lg">{{ $order->customer_name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-600">Email</p>
                                <p class="text-lg">{{ $order->customer_email }}</p>
                            </div>
                            
                            @if($order->customer_phone)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-600">Phone</p>
                                    <p class="text-lg">{{ $order->customer_phone }}</p>
                                </div>
                            @endif
                            
                            @if($order->notes)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-600">Order Notes</p>
                                    <p class="text-gray-700">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
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
                                        <p class="text-green-600 font-semibold">${{ number_format($item->unit_price, 2) }} each</p>
                                    </div>
                                    
                                    <!-- Quantity and Total -->
                                    <div class="text-right">
                                        <p class="font-semibold">Qty: {{ $item->qty }}</p>
                                        <p class="text-lg font-semibold text-green-600">${{ number_format($item->line_total, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Tax:</span>
                                <span>${{ number_format($order->tax_total, 2) }}</span>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total:</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Order Status Timeline -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="font-semibold mb-3">Order Status</h4>
                            
                            <div class="space-y-2 text-sm">
                                @if($order->status === 'pending')
                                    <div class="flex items-center text-yellow-600">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        Order is pending confirmation
                                    </div>
                                @elseif($order->status === 'confirmed')
                                    <div class="flex items-center text-blue-600">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        Order confirmed and being prepared
                                    </div>
                                @elseif($order->status === 'processing')
                                    <div class="flex items-center text-blue-600">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        Order is being processed
                                    </div>
                                @elseif($order->status === 'ready')
                                    <div class="flex items-center text-purple-600">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        Order is ready for pickup
                                    </div>
                                @elseif($order->status === 'completed')
                                    <div class="flex items-center text-green-600">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        Order completed
                                    </div>
                                @elseif($order->status === 'cancelled')
                                    <div class="flex items-center text-red-600">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        Order cancelled
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            @if($order->isCompleted() || $order->isCancelled())
                                <a href="{{ route('customer.shop.index') }}" 
                                   class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                    Shop Again
                                </a>
                            @endif
                            
                            <a href="{{ route('customer.orders.index') }}" 
                               class="block w-full bg-gray-200 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-300 transition-colors">
                                View All Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>