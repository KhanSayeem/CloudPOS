<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index()
    {
        $orders = Order::forUser(auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');
        
        return view('customer.orders.show', compact('order'));
    }

    public function checkout()
    {
        $cartSummary = $this->cartService->getCartSummary();
        
        if ($cartSummary['count'] === 0) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        return view('customer.orders.checkout', $cartSummary);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500'
        ]);

        $cartSummary = $this->cartService->getCartSummary();
        
        if ($cartSummary['count'] === 0) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $cartSummary['subtotal'],
                'tax_total' => $cartSummary['tax_total'],
                'total' => $cartSummary['total'],
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($cartSummary['items'] as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'qty' => $cartItem->qty,
                    'unit_price' => $cartItem->unit_price,
                    'line_total' => $cartItem->line_total
                ]);
            }

            // Clear cart
            $this->cartService->clearCart();

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order placed successfully! Order #' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function cancel(Order $order)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Order cancelled successfully.');
    }
}