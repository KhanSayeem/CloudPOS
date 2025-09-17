<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Order Status and Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Order Status</h3>
                        <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full
                            @switch($order->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('confirmed') bg-blue-100 text-blue-800 @break
                                @case('processing') bg-purple-100 text-purple-800 @break
                                @case('ready') bg-green-100 text-green-800 @break
                                @case('completed') bg-gray-100 text-gray-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                            @endswitch
                        ">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    @if(!in_array($order->status, ['completed', 'cancelled']))
                        <div class="space-y-2">
                            <h4 class="text-sm font-medium text-gray-700">Quick Actions</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            Confirm Order
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'confirmed')
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="processing">
                                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            Start Processing
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'processing')
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ready">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            Mark Ready
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'ready')
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            Complete Order
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors" onclick="return confirm('Are you sure you want to cancel this order?')">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Manual Status Update -->
                @if(!in_array($order->status, ['completed', 'cancelled']))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Manual Status Update</h4>
                        <form method="POST" action="{{ route('orders.update-status', $order) }}" class="flex gap-3 items-end">
                            @csrf
                            @method('PATCH')
                            <div>
                                <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Status</option>
                                    @if($order->status !== 'pending')
                                        <option value="pending">Pending</option>
                                    @endif
                                    @if($order->status !== 'confirmed')
                                        <option value="confirmed">Confirmed</option>
                                    @endif
                                    @if($order->status !== 'processing')
                                        <option value="processing">Processing</option>
                                    @endif
                                    @if($order->status !== 'ready')
                                        <option value="ready">Ready</option>
                                    @endif
                                    @if($order->status !== 'completed')
                                        <option value="completed">Completed</option>
                                    @endif
                                    @if($order->status !== 'cancelled')
                                        <option value="cancelled">Cancelled</option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="text-sm text-gray-900">{{ $order->customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $order->customer_email }}</dd>
                        </div>
                        @if($order->customer_phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $order->customer_phone }}</dd>
                            </div>
                        @endif
                        @if($order->user)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account</dt>
                                <dd class="text-sm text-gray-900">{{ $order->user->name }} (ID: {{ $order->user->id }})</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Order Summary -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                            <dd class="text-sm text-gray-900">{{ $order->order_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                            <dd class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($order->subtotal, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tax</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($order->tax_total, 2) }}</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <dt class="text-sm font-medium text-gray-500">Total</dt>
                            <dd class="text-lg font-bold text-gray-900">${{ number_format($order->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        @if($item->product->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($item->product->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->qty }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">${{ number_format($item->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Order Notes</h3>
                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-md">{{ $order->notes }}</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>