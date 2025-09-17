<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Order Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-yellow-100 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-yellow-800">Pending</h3>
                    <p class="text-2xl font-bold text-yellow-900">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="bg-blue-100 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800">Confirmed</h3>
                    <p class="text-2xl font-bold text-blue-900">{{ $statusCounts['confirmed'] }}</p>
                </div>
                <div class="bg-purple-100 border border-purple-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-purple-800">Processing</h3>
                    <p class="text-2xl font-bold text-purple-900">{{ $statusCounts['processing'] }}</p>
                </div>
                <div class="bg-green-100 border border-green-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-green-800">Ready</h3>
                    <p class="text-2xl font-bold text-green-900">{{ $statusCounts['ready'] }}</p>
                </div>
                <div class="bg-gray-100 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-800">Completed</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['completed'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Order number, customer name or email..."
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <div class="min-w-[150px]">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($orders->count() > 0)
                    <form id="bulkForm" method="POST" action="{{ route('orders.bulk-update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Bulk Actions -->
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="selectAll" class="ml-2 text-sm text-gray-600">Select All</label>
                                </div>
                                <select name="status" id="bulkStatus" class="border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">Bulk Status Update</option>
                                    <option value="confirmed">Mark as Confirmed</option>
                                    <option value="processing">Mark as Processing</option>
                                    <option value="ready">Mark as Ready</option>
                                    <option value="completed">Mark as Completed</option>
                                    <option value="cancelled">Mark as Cancelled</option>
                                </select>
                                <button type="submit" id="bulkSubmit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm transition-colors" disabled>
                                    Update Selected
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" class="rounded border-gray-300">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox rounded border-gray-300 text-indigo-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->items->count() }} items</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                ${{ number_format($order->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('M j, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 text-lg">No orders found</div>
                        <div class="text-gray-400 text-sm mt-2">Orders will appear here when customers place them</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');
            const bulkSubmit = document.getElementById('bulkSubmit');
            const bulkStatus = document.getElementById('bulkStatus');

            function updateBulkSubmitState() {
                const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
                const hasSelection = checkedBoxes.length > 0;
                const hasStatus = bulkStatus.value !== '';

                bulkSubmit.disabled = !(hasSelection && hasStatus);
            }

            selectAll.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkSubmitState();
            });

            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(orderCheckboxes).every(cb => cb.checked);
                    const noneChecked = Array.from(orderCheckboxes).every(cb => !cb.checked);

                    selectAll.checked = allChecked;
                    selectAll.indeterminate = !allChecked && !noneChecked;

                    updateBulkSubmitState();
                });
            });

            bulkStatus.addEventListener('change', updateBulkSubmitState);
        });
    </script>
</x-app-layout>