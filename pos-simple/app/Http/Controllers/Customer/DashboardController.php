<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;

class DashboardController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index()
    {
        $user = auth()->user();
        
        // Get recent orders
        $recentOrders = Order::forUser($user->id)
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // Get order statistics
        $orderStats = [
            'total_orders' => Order::forUser($user->id)->count(),
            'pending_orders' => Order::forUser($user->id)->byStatus(Order::STATUS_PENDING)->count(),
            'completed_orders' => Order::forUser($user->id)->byStatus(Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::forUser($user->id)->byStatus(Order::STATUS_CANCELLED)->count(),
        ];

        $cartCount = $this->cartService->getCartItemCount();

        return view('customer.dashboard', compact('recentOrders', 'orderStats', 'cartCount'));
    }
}