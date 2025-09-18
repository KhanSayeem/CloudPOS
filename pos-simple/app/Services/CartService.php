<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart(): Collection
    {
        if (Auth::check()) {
            return Cart::forUser(Auth::id())
                ->with('product')
                ->get();
        }

        return Cart::forSession(session()->getId())
            ->with('product')
            ->get();
    }

    public function addToCart(Product $product, int $quantity = 1): bool
    {
        try {
            $identifier = Auth::check() ? 
                ['user_id' => Auth::id()] : 
                ['session_id' => session()->getId()];

            $cartItem = Cart::where($identifier)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                // Update existing cart item
                $cartItem->update([
                    'qty' => $cartItem->qty + $quantity,
                    'unit_price' => $product->price
                ]);
            } else {
                // Create new cart item
                Cart::create(array_merge($identifier, [
                    'product_id' => $product->id,
                    'qty' => $quantity,
                    'unit_price' => $product->price
                ]));
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateCartItem(int $cartItemId, int $quantity): bool
    {
        try {
            $identifier = Auth::check() ? 
                ['user_id' => Auth::id()] : 
                ['session_id' => session()->getId()];

            $cartItem = Cart::where($identifier)
                ->where('id', $cartItemId)
                ->first();

            if (!$cartItem) {
                return false;
            }

            if ($quantity <= 0) {
                return $this->removeFromCart($cartItemId);
            }

            $cartItem->update(['qty' => $quantity]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeFromCart(int $cartItemId): bool
    {
        try {
            $identifier = Auth::check() ? 
                ['user_id' => Auth::id()] : 
                ['session_id' => session()->getId()];

            return Cart::where($identifier)
                ->where('id', $cartItemId)
                ->delete() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function clearCart(): bool
    {
        try {
            $identifier = Auth::check() ? 
                ['user_id' => Auth::id()] : 
                ['session_id' => session()->getId()];

            Cart::where($identifier)->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCartSummary(): array
    {
        $cart = $this->getCart();
        
        $subtotal = $cart->sum('line_total');
        $taxRate = 0.10; // 10% tax
        $taxTotal = $subtotal * $taxRate;
        $total = $subtotal + $taxTotal;

        return [
            'items' => $cart,
            'count' => $cart->sum('qty'),
            'subtotal' => round($subtotal, 2),
            'tax_rate' => $taxRate,
            'tax_total' => round($taxTotal, 2),
            'total' => round($total, 2)
        ];
    }

    public function getCartItemCount(): int
    {
        $identifier = Auth::check() ? 
            ['user_id' => Auth::id()] : 
            ['session_id' => session()->getId()];

        return Cart::where($identifier)->sum('qty');
    }
}