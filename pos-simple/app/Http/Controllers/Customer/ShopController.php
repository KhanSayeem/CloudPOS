<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\CartService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::orderBy('name')->get();
        $cartCount = $this->cartService->getCartItemCount();

        return view('customer.shop.index', compact('products', 'categories', 'cartCount'));
    }

    public function show(Product $product)
    {
        if (!$product->status || $product->stock <= 0) {
            abort(404);
        }

        $cartCount = $this->cartService->getCartItemCount();
        
        return view('customer.shop.show', compact('product', 'cartCount'));
    }
}