<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Daily Sales -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">$</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Today's Sales</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['daily_sales'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-gray-600">{{ $stats['daily_transactions'] }} transactions today</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">📊</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['monthly_sales'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            @if($stats['growth_percentage'] > 0)
                                <span class="text-green-600">↑ {{ number_format($stats['growth_percentage'], 1) }}% from last month</span>
                            @elseif($stats['growth_percentage'] < 0)
                                <span class="text-red-600">↓ {{ number_format(abs($stats['growth_percentage']), 1) }}% from last month</span>
                            @else
                                <span class="text-gray-600">No change from last month</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">📦</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_products']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            @if($stats['low_stock_products'] > 0)
                                <span class="text-yellow-600">{{ $stats['low_stock_products'] }} low stock alerts</span>
                            @else
                                <span class="text-green-600">All products well stocked</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">👥</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-gray-600">{{ $stats['active_users'] }} active users</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white shadow rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detailed Reports</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.reports.sales') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600">📈</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Sales Analysis</h4>
                                    <p class="text-sm text-gray-500">View detailed sales trends and patterns</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports.revenue') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600">💰</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Revenue Report</h4>
                                    <p class="text-sm text-gray-500">Analyze revenue and profit margins</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports.products') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <span class="text-purple-600">📊</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Product Performance</h4>
                                    <p class="text-sm text-gray-500">Track best and worst performing products</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Sales -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Sales</h3>
                        <div class="flow-root">
                            <ul class="-my-3 divide-y divide-gray-200">
                                @forelse($recentSales as $sale)
                                    <li class="py-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-600">#{{ $sale->id }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    ${{ number_format($sale->total, 2) }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $sale->created_at->format('M d, Y g:i A') }} • {{ $sale->user?->name ?? 'Unknown' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-3 text-center text-gray-500 text-sm">No recent sales</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Products This Month</h3>
                        <div class="flow-root">
                            <ul class="-my-3 divide-y divide-gray-200">
                                @forelse($topProducts as $product)
                                    <li class="py-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-600">{{ $product->total_sold }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    Revenue: ${{ number_format($product->total_revenue, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="text-sm text-gray-500">{{ $product->total_sold }} sold</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-3 text-center text-gray-500 text-sm">No sales data available</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>