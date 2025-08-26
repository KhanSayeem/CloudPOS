<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show recent sales, newest first
        $sales = Sale::with('user')->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        // Load items, products, payments, and user
        $sale->load('items.product', 'payments', 'user');
        return view('sales.show', compact('sale'));
    }

    /**
     * Generate a PDF receipt.
     */
    public function receipt(Sale $sale)
    {
        $sale->load('items.product', 'payments', 'user');

        // Requires: composer require barryvdh/laravel-dompdf
        $pdf = \PDF::loadView('sales.receipt', compact('sale'));

        return $pdf->download("receipt-{$sale->id}.pdf");
    }

    // We don’t need create/store/edit/update/destroy
    // because sales are created through the POS cart.
}
