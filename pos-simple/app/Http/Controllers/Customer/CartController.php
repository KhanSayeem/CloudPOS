<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index()
    {
        $cartSummary = $this->cartService->getCartSummary();
        
        return view('customer.cart.index', $cartSummary);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        if (!$product->inStock()) {
            return back()->with('error', 'Product is not available.');
        }

        $success = $this->cartService->addToCart($product, $request->quantity);

        if ($success) {
            return back()->with([
                'success' => 'Product added to cart!',
                'cart_updated' => true
            ]);
        } else {
            return back()->with('error', 'Failed to add product to cart.');
        }
    }

    public function update(Request $request, int $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:999'
        ]);

        $success = $this->cartService->updateCartItem($cartItemId, $request->quantity);

        if ($success) {
            return back()->with('success', 'Cart updated!');
        } else {
            return back()->with('error', 'Failed to update cart.');
        }
    }

    public function remove(int $cartItemId)
    {
        $success = $this->cartService->removeFromCart($cartItemId);

        if ($success) {
            return back()->with('success', 'Item removed from cart!');
        } else {
            return back()->with('error', 'Failed to remove item from cart.');
        }
    }

    public function clear()
    {
        $success = $this->cartService->clearCart();

        if ($success) {
            return redirect()->route('customer.cart.index')->with('success', 'Cart cleared!');
        } else {
            return back()->with('error', 'Failed to clear cart.');
        }
    }
}