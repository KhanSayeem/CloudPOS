<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $products = Product::query()
            ->when($q, fn ($qry) =>
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%")
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('products.index', compact('products', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sku'         => ['required','string','max:64','unique:products,sku'],
            'barcode'     => ['nullable','string','max:64'],
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'status'      => ['nullable','boolean'],
        ]);

        // Checkbox handling
        $data['status'] = $request->boolean('status');

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Optional: you can have a read-only view
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku'         => ['required','string','max:64', Rule::unique('products','sku')->ignore($product->id)],
            'barcode'     => ['nullable','string','max:64'],
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'status'      => ['nullable','boolean'],
        ]);

        $data['status'] = $request->boolean('status');

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted.');
    }
}
