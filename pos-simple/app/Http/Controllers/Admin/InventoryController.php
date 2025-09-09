<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $categoryId = $request->get('category');
        
        $query = Product::with('category');
        
        switch ($filter) {
            case 'low_stock':
                $query->lowStock();
                break;
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
            case 'inactive':
                $query->where('status', false);
                break;
            default:
                $query->active();
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->paginate(20);
        $categories = Category::active()->get();
        
        $stats = [
            'total_products' => Product::count(),
            'low_stock_count' => Product::lowStock()->count(),
            'out_of_stock_count' => Product::where('stock', 0)->count(),
            'total_value' => Product::selectRaw('SUM(stock * price) as total')->value('total') ?? 0,
        ];
        
        return view('admin.inventory.index', compact('products', 'categories', 'stats', 'filter', 'categoryId'));
    }

    public function show(Product $inventory)
    {
        $inventory->load('category');
        return view('admin.inventory.show', compact('inventory'));
    }

    public function edit(Product $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Product $inventory)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255'
        ]);

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory updated successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock' => 'required|integer|min:0'
        ]);

        foreach ($validated['products'] as $productData) {
            Product::find($productData['id'])->update([
                'stock' => $productData['stock']
            ]);
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Bulk inventory update completed.');
    }
}