<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        // Get status counts for dashboard stats
        $statusCounts = [
            'pending' => Order::byStatus(Order::STATUS_PENDING)->count(),
            'confirmed' => Order::byStatus(Order::STATUS_CONFIRMED)->count(),
            'processing' => Order::byStatus(Order::STATUS_PROCESSING)->count(),
            'ready' => Order::byStatus(Order::STATUS_READY)->count(),
            'completed' => Order::byStatus(Order::STATUS_COMPLETED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED
            ])
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validate status transitions
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            return back()->with('error', "Cannot change status from {$oldStatus} to {$newStatus}");
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Order status updated to {$order->status_label}");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_CONFIRMED,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED
            ])
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $updatedCount = 0;
        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            if ($this->isValidStatusTransition($order->status, $request->status)) {
                $order->update(['status' => $request->status]);
                $updatedCount++;
            }
        }

        if ($updatedCount > 0) {
            return back()->with('success', "Updated {$updatedCount} orders to {$request->status}");
        } else {
            return back()->with('error', 'No orders could be updated with the selected status');
        }
    }

    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        // Define valid status transitions
        $validTransitions = [
            Order::STATUS_PENDING => [
                Order::STATUS_CONFIRMED,
                Order::STATUS_CANCELLED
            ],
            Order::STATUS_CONFIRMED => [
                Order::STATUS_PROCESSING,
                Order::STATUS_CANCELLED
            ],
            Order::STATUS_PROCESSING => [
                Order::STATUS_READY,
                Order::STATUS_CANCELLED
            ],
            Order::STATUS_READY => [
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED
            ],
            Order::STATUS_COMPLETED => [],
            Order::STATUS_CANCELLED => []
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}