<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = Sale::with(['user', 'payments', 'items.product']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total_transactions' => Sale::count(),
            'today_transactions' => Sale::whereDate('created_at', Carbon::today())->count(),
            'total_revenue' => Sale::sum('total'),
            'today_revenue' => Sale::whereDate('created_at', Carbon::today())->sum('total'),
        ];
        
        return view('admin.transactions.index', compact('transactions', 'stats', 'search', 'status', 'dateFrom', 'dateTo'));
    }

    public function show(Sale $transaction)
    {
        $transaction->load(['user', 'customer', 'payments', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function refund(Request $request, Sale $transaction)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:' . $transaction->total
        ]);

        // Create refund record (you might want to create a separate refunds table)
        $refund = Payment::create([
            'sale_id' => $transaction->id,
            'method' => 'refund',
            'amount' => -abs($validated['amount']), // Negative for refund
            'reference' => 'REFUND-' . time(),
            'notes' => $validated['reason']
        ]);

        return redirect()->route('admin.transactions.show', $transaction)
            ->with('success', 'Refund of $' . number_format($validated['amount'], 2) . ' has been processed.');
    }

    public function void(Sale $transaction)
    {
        if ($transaction->created_at->diffInHours(now()) > 24) {
            return redirect()->route('admin.transactions.show', $transaction)
                ->with('error', 'Cannot void transactions older than 24 hours.');
        }

        // Mark as void (you might want to add a status column to sales table)
        $transaction->update(['notes' => 'VOIDED by ' . auth()->user()->name . ' on ' . now()]);
        
        // Create void payment record
        Payment::create([
            'sale_id' => $transaction->id,
            'method' => 'void',
            'amount' => -$transaction->total,
            'reference' => 'VOID-' . time(),
            'notes' => 'Transaction voided'
        ]);

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction #' . $transaction->id . ' has been voided.');
    }

    public function receipt(Sale $transaction)
    {
        $transaction->load(['user', 'customer', 'payments', 'items.product']);
        return view('admin.transactions.receipt', compact('transaction'));
    }
}
