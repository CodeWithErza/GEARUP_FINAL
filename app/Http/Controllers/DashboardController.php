<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $lowStockProducts = Product::with('category')
            ->whereRaw('stock <= reorder_level')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // Fetch the top 5 products by sales quantity
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity,
                    'total_sales' => $item->total_sales
                ];
            });

        $recentOrders = Order::with(['customer', 'items'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer->name,
                    'date' => $order->created_at->format('M d, Y'),
                    'total' => number_format($order->total, 2),
                    'status' => $order->status,
                    'items_count' => $order->items->count()
                ];
            });

        return view('dashboard.index', compact('totalProducts', 'totalCustomers', 'lowStockProducts', 'recentOrders', 'topProducts'));
    }
    
    /**
     * Get dashboard data via AJAX
     */
    public function dashboardData()
    {
        // Get today's sales and orders
        $today = now()->startOfDay();
        $todaySales = Order::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total');
        
        $todayOrders = Order::whereDate('created_at', $today)->count();
        
        // Get recent orders
        $recentOrders = Order::with(['customer', 'items'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->format('M d, Y'),
                    'items_count' => $order->items->count(),
                    'total' => number_format($order->total, 2),
                    'status' => $order->status
                ];
            });
            
        // Get top products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity,
                    'total_sales' => $item->total_sales
                ];
            });
            
        return response()->json([
            'today_sales' => number_format($todaySales, 2),
            'today_orders' => $todayOrders,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts
        ]);
    }
} 