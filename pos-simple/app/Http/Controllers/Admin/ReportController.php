<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $stats = [
            // Daily stats
            'daily_sales' => Sale::whereDate('created_at', $today)->sum('total'),
            'daily_transactions' => Sale::whereDate('created_at', $today)->count(),
            
            // Monthly stats  
            'monthly_sales' => Sale::where('created_at', '>=', $thisMonth)->sum('total'),
            'monthly_transactions' => Sale::where('created_at', '>=', $thisMonth)->count(),
            
            // Growth comparison
            'last_month_sales' => Sale::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total'),
            
            // Product stats
            'total_products' => Product::count(),
            'low_stock_products' => Product::lowStock()->count(),
            
            // User stats
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
        ];
        
        // Calculate growth percentage
        $stats['growth_percentage'] = $stats['last_month_sales'] > 0 
            ? (($stats['monthly_sales'] - $stats['last_month_sales']) / $stats['last_month_sales']) * 100
            : 0;
            
        // Recent sales
        $recentSales = Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Top products this month (align with columns: qty, line_total)
        $topProducts = collect();
        if (SaleItem::exists()) {
            $topProducts = SaleItem::select(
                    'sale_items.product_id',
                    'products.name',
                    DB::raw('SUM(sale_items.qty) as total_sold'),
                    DB::raw('SUM(sale_items.line_total) as total_revenue')
                )
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.created_at', '>=', $thisMonth)
                ->groupBy('sale_items.product_id', 'products.name')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get();
        }
            
        return view('admin.reports.index', compact('stats', 'recentSales', 'topProducts'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'day');
        
        $query = Sale::query()
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
            
        // Group by time period with SQLite/MySQL compatibility
        $driver = DB::connection()->getDriverName();
        $salesData = collect();
        if ($groupBy === 'hour') {
            if ($driver === 'sqlite') {
                $salesData = $query->select(
                        DB::raw("strftime('%Y-%m-%d', created_at) as date"),
                        DB::raw("strftime('%H', created_at) as period"),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('date', 'period')
                    ->orderBy('date')
                    ->orderBy('period')
                    ->get();
            } else {
                $salesData = $query->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('HOUR(created_at) as period'),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('date', 'period')
                    ->orderBy('date')
                    ->orderBy('period')
                    ->get();
            }
        } elseif ($groupBy === 'week') {
            if ($driver === 'sqlite') {
                $salesData = $query->select(
                        DB::raw("strftime('%Y-W%W', created_at) as period"),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            } else {
                $salesData = $query->select(
                        DB::raw('YEARWEEK(created_at) as period'),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            }
        } elseif ($groupBy === 'month') {
            if ($driver === 'sqlite') {
                $salesData = $query->select(
                        DB::raw("strftime('%Y', created_at) as year"),
                        DB::raw("strftime('%m', created_at) as month"),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
            } else {
                $salesData = $query->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
            }
        } else { // day
            if ($driver === 'sqlite') {
                $salesData = $query->select(
                        DB::raw("strftime('%Y-%m-%d', created_at) as period"),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            } else {
                $salesData = $query->select(
                        DB::raw('DATE(created_at) as period'),
                        DB::raw('SUM(total) as total_sales'),
                        DB::raw('COUNT(*) as transaction_count')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            }
        }
        
        $totals = [
            'total_sales' => $salesData->sum('total_sales'),
            'total_transactions' => $salesData->sum('transaction_count'),
            'average_transaction' => $salesData->sum('transaction_count') > 0 
                ? $salesData->sum('total_sales') / $salesData->sum('transaction_count') 
                : 0
        ];
        
        return view('admin.reports.sales', compact('salesData', 'totals', 'startDate', 'endDate', 'groupBy'));
    }

    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $revenueData = collect();
        if (SaleItem::exists()) {
            $revenueData = SaleItem::select(
                    'products.name',
                    'products.sku',
                    'products.price',
                    'products.cost_price',
                    DB::raw('SUM(sale_items.qty) as total_sold'),
                    DB::raw('SUM(sale_items.line_total) as total_revenue'),
                    DB::raw('SUM(sale_items.qty * COALESCE(products.cost_price, 0)) as total_cost')
                )
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->whereBetween('sales.created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ])
                ->groupBy('sale_items.product_id', 'products.name', 'products.sku', 'products.price', 'products.cost_price')
                ->orderBy('total_revenue', 'desc')
                ->paginate(20);
        } else {
            $revenueData = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                20,
                1,
                ['path' => request()->url()]
            );
        }
            
        // Calculate profit for each item
        $revenueData->getCollection()->transform(function ($item) {
            $item->total_profit = $item->total_revenue - ($item->total_cost ?? 0);
            $item->profit_margin = $item->total_revenue > 0 
                ? (($item->total_profit / $item->total_revenue) * 100) 
                : 0;
            return $item;
        });
        
        $summary = [
            'total_revenue' => $revenueData->sum('total_revenue'),
            'total_cost' => $revenueData->sum('total_cost'),
            'total_profit' => $revenueData->sum('total_profit'),
        ];
        
        return view('admin.reports.revenue', compact('revenueData', 'summary', 'startDate', 'endDate'));
    }

    public function products(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $productStats = Product::select(
                'products.*',
                DB::raw('COALESCE(SUM(sale_items.qty), 0) as total_sold'),
                DB::raw('COALESCE(SUM(sale_items.line_total), 0) as total_revenue')
            )
            ->leftJoin('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('sales', function($join) use ($startDate) {
                $join->on('sale_items.sale_id', '=', 'sales.id')
                     ->where('sales.created_at', '>=', $startDate);
            })
            ->groupBy('products.id', 'products.sku', 'products.barcode', 'products.name', 'products.description', 
                     'products.image', 'products.price', 'products.cost_price', 'products.stock', 
                     'products.min_stock', 'products.max_stock', 'products.supplier', 'products.status', 
                     'products.category_id', 'products.created_at', 'products.updated_at')
            ->orderBy('total_sold', 'desc')
            ->paginate(20);
            
        return view('admin.reports.products', compact('productStats', 'period'));
    }
}
