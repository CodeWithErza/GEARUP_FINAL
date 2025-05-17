<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display the sales reports page
     */
    public function sales()
    {
        return view('reports.sales');
    }
    
    /**
     * Export sales report in the specified format
     */
    public function exportOrders(Request $request)
    {
        $format = $request->route()->defaults['format'] ?? 'pdf';
        $dateRange = $request->input('date_range', 'this_month');
        $productCategory = $request->input('product_category', 'all');
        
        // Process the date range
        $startDate = now();
        $endDate = now();

        switch ($dateRange) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                break;
        }
        
        // Query sales by category
        $salesByCategory = OrderItem::select(
            'categories.id as category_id',
            'categories.name as category_name',
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.subtotal) as total_sales')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'completed')
        ->whereBetween('orders.created_at', [$startDate, $endDate]);

        if ($productCategory !== 'all') {
            $salesByCategory->where('categories.name', $productCategory);
        }

        $salesByCategory = $salesByCategory->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        $totalSales = $salesByCategory->sum('total_sales');
        $totalOrders = $salesByCategory->sum('total_orders');
        $totalQuantity = $salesByCategory->sum('total_quantity');

        $salesByCategory = $salesByCategory->map(function ($item) use ($totalSales) {
            $item->percentage = $totalSales > 0 ? round(($item->total_sales / $totalSales) * 100, 1) : 0;
            return $item;
        });

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.sales_report_pdf', [
                'salesByCategory' => $salesByCategory,
                'totalSales' => $totalSales,
                'totalOrders' => $totalOrders,
                'totalQuantity' => $totalQuantity,
                'dateRange' => $dateRange,
                'productCategory' => $productCategory,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
            return $pdf->download('sales_report_' . $dateRange . '.pdf');
        }
        
        if ($format === 'excel') {
            return Excel::download(
                new SalesReportExport(
                    $salesByCategory,
                    $totalSales,
                    $totalOrders,
                    $totalQuantity,
                    $dateRange,
                    $productCategory,
                    $startDate,
                    $endDate
                ),
                'sales_report_' . $dateRange . '.xlsx'
            );
        }
        
        return back()->with('error', 'Invalid export format.');
    }

    /**
     * Get sales data grouped by category
     */
    public function getSalesByCategory(Request $request)
    {
        try {
            $dateRange = $request->input('date_range', 'this_month');
            $startDate = now();
            $endDate = now();

            switch ($dateRange) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay();
                    $endDate = now()->subDay()->endOfDay();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'last_week':
                    $startDate = now()->subWeek()->startOfWeek();
                    $endDate = now()->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'last_month':
                    $startDate = now()->subMonth()->startOfMonth();
                    $endDate = now()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
                case 'custom':
                    $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                    $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                    break;
            }

            // Get sales by category with additional metrics
            $salesByCategory = OrderItem::select(
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

            // Calculate totals
            $totalSales = $salesByCategory->sum('total_sales');
            $totalOrders = $salesByCategory->sum('total_orders');
            $totalQuantity = $salesByCategory->sum('total_quantity');

            // Add percentage calculations
            $salesByCategory = $salesByCategory->map(function ($item) use ($totalSales) {
                $item->percentage = $totalSales > 0 ? round(($item->total_sales / $totalSales) * 100, 1) : 0;
                return $item;
            });

            return response()->json([
                'success' => true,
                'sales_by_category' => $salesByCategory,
                'summary' => [
                    'total_sales' => $totalSales,
                    'total_orders' => $totalOrders,
                    'total_quantity' => $totalQuantity,
                ],
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getSalesByCategory: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales by category data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent sales data
     */
    public function getRecentSales(Request $request)
    {
        try {
            $limit = $request->input('limit', 5);
            
            $recentSales = Order::with(['customer', 'items.product'])
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($order) {
                    return [
                        'order_id' => $order->order_number,
                        'customer_name' => $order->customer ? $order->customer->name : 'N/A',
                        'date' => $order->created_at->format('F d, Y'),
                        'time' => $order->created_at->format('h:i A'),
                        'total_items' => $order->items ? $order->items->count() : 0,
                        'total_amount' => $order->total ?? 0,
                        'status' => $order->status ?? 'unknown',
                        'items' => $order->items ? $order->items->map(function ($item) {
                            return [
                                'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                                'quantity' => $item->quantity ?? 0,
                                'price' => $item->price ?? 0,
                                'subtotal' => $item->subtotal ?? 0
                            ];
                        }) : []
                    ];
                });

            return response()->json([
                'recent_sales' => $recentSales,
                'success' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getRecentSales: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => 'Error fetching recent sales data',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Get sales details data with product and category information
     */
    public function getSalesDetails(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = now();
        $endDate = now();

        switch ($dateRange) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                break;
        }

        // Get search query if any
        $search = $request->input('search');

        $baseQuery = OrderItem::select(
                'products.id as product_id',
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('products.price as unit_price'),
                DB::raw('SUM(order_items.subtotal) as total_sales'),
                DB::raw('MAX(orders.created_at) as last_sale_date')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate]);

        // Apply search filter if provided
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('categories.name', 'like', "%{$search}%");
            });
        }

        // Clone the query for summary calculations
        $summaryQuery = clone $baseQuery;
        
        // Get summary totals (all matching records)
        $summary = $summaryQuery->select(
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.subtotal) as total_sales')
        )->first();

        // Execute the main query with pagination
        $salesDetails = $baseQuery->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'categories.name'
            )
            ->orderBy('total_sales', 'desc')
            ->paginate(10);

        return response()->json([
            'sales_details' => $salesDetails,
            'summary' => [
                'total_quantity' => $summary->total_quantity ?? 0,
                'total_sales' => $summary->total_sales ?? 0
            ],
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get expense details data from stockin records
     */
    public function getExpenseDetails(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = now();
        $endDate = now();

        switch ($dateRange) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                break;
        }

        // Get search query if any
        $search = $request->input('search');

        $query = DB::table('stockin_items')
            ->select(
                'stockins.id as stockin_id',
                'stockins.reference_number',
                'stockins.created_at as date',
                'suppliers.name as supplier_name',
                'products.name as product_name',
                'categories.name as category_name',
                'stockin_items.quantity',
                'stockin_items.unit_cost',
                DB::raw('stockin_items.quantity * stockin_items.unit_cost as total_cost')
            )
            ->join('stockins', 'stockin_items.stockin_id', '=', 'stockins.id')
            ->join('suppliers', 'stockins.supplier_id', '=', 'suppliers.id')
            ->join('products', 'stockin_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('stockins.created_at', [$startDate, $endDate]);

        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('categories.name', 'like', "%{$search}%")
                  ->orWhere('suppliers.name', 'like', "%{$search}%")
                  ->orWhere('stockins.reference_number', 'like', "%{$search}%");
            });
        }

        $expenseDetails = $query
            ->orderBy('stockins.created_at', 'desc')
            ->paginate(10);

        // Calculate summary statistics
        $summary = DB::table('stockin_items')
            ->join('stockins', 'stockin_items.stockin_id', '=', 'stockins.id')
            ->whereBetween('stockins.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(DISTINCT stockins.id) as total_transactions'),
                DB::raw('SUM(stockin_items.quantity) as total_items'),
                DB::raw('SUM(stockin_items.quantity * stockin_items.unit_cost) as total_cost')
            )
            ->first();

        return response()->json([
            'expense_details' => $expenseDetails,
            'summary' => $summary,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get sales summary data for dashboard cards
     */
    public function getSalesSummary(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = now();
        $endDate = now();
        $previousStartDate = now();
        $previousEndDate = now();

        switch ($dateRange) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $previousStartDate = now()->subDay()->startOfDay();
                $previousEndDate = now()->subDay()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                $previousStartDate = now()->subDays(2)->startOfDay();
                $previousEndDate = now()->subDays(2)->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                $previousStartDate = now()->subWeek()->startOfWeek();
                $previousEndDate = now()->subWeek()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                $previousStartDate = now()->subWeeks(2)->startOfWeek();
                $previousEndDate = now()->subWeeks(2)->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $previousStartDate = now()->subMonth()->startOfMonth();
                $previousEndDate = now()->subMonth()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                $previousStartDate = now()->subMonths(2)->startOfMonth();
                $previousEndDate = now()->subMonths(2)->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $previousStartDate = now()->subYear()->startOfYear();
                $previousEndDate = now()->subYear()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                
                // Calculate the same duration for the previous period
                $daysDifference = $startDate->diffInDays($endDate) + 1;
                $previousEndDate = $startDate->copy()->subDay();
                $previousStartDate = $previousEndDate->copy()->subDays($daysDifference - 1);
                break;
        }

        // Get current period data
        $totalSales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
            
        $totalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $totalProductsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.quantity');
            
        // Calculate average order value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Get previous period data for percentage comparison
        $previousTotalSales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('total');
            
        $previousTotalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
            
        $previousTotalProductsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$previousStartDate, $previousEndDate])
            ->sum('order_items.quantity');
            
        $previousAverageOrderValue = $previousTotalOrders > 0 ? $previousTotalSales / $previousTotalOrders : 0;
        
        // Calculate percentage changes
        $salesPercentageChange = $previousTotalSales > 0 
            ? round((($totalSales - $previousTotalSales) / $previousTotalSales) * 100, 1) 
            : 100;
            
        $ordersPercentageChange = $previousTotalOrders > 0 
            ? round((($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100, 1) 
            : 100;
            
        $averageOrderValuePercentageChange = $previousAverageOrderValue > 0 
            ? round((($averageOrderValue - $previousAverageOrderValue) / $previousAverageOrderValue) * 100, 1) 
            : 100;
            
        $productsSoldPercentageChange = $previousTotalProductsSold > 0 
            ? round((($totalProductsSold - $previousTotalProductsSold) / $previousTotalProductsSold) * 100, 1) 
            : 100;

        return response()->json([
            'success' => true,
            'total_sales' => $totalSales,
            'total_sales_formatted' => number_format($totalSales, 2),
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'average_order_value_formatted' => number_format($averageOrderValue, 2),
            'total_products_sold' => $totalProductsSold,
            'sales_percentage_change' => $salesPercentageChange,
            'orders_percentage_change' => $ordersPercentageChange,
            'average_order_value_percentage_change' => $averageOrderValuePercentageChange,
            'products_sold_percentage_change' => $productsSoldPercentageChange,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get monthly sales trend data for the current year
     */
    public function getSalesTrend(Request $request)
    {
        $year = $request->input('year', now()->year);
        
        $monthlySales = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total_sales')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();
            
        // Create an array with all months, defaulting to 0 for months with no sales
        $salesByMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesByMonth[$i] = 0;
        }
        
        // Fill in actual sales data where available
        foreach ($monthlySales as $sale) {
            $salesByMonth[$sale->month] = (float) $sale->total_sales;
        }
        
        // Get month names
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
        }
        
        return response()->json([
            'labels' => $months,
            'data' => array_values($salesByMonth),
            'year' => $year
        ]);
    }

    /**
     * Get top selling products data
     */
    public function getTopSellingProducts(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $limit = $request->input('limit', 5);
        $startDate = now();
        $endDate = now();

        switch ($dateRange) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'yesterday':
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? now()->parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->input('end_date') ? now()->parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();
                break;
        }
        
        $topProducts = OrderItem::select(
                'products.name as product_name',
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get();
            
        return response()->json([
            'labels' => $topProducts->pluck('product_name')->toArray(),
            'data' => $topProducts->pluck('total_sales')->toArray(),
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }
} 