<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Stockin;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display the inventory dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get low stock products
        $lowStockProducts = Product::whereRaw('stock <= reorder_level')
            ->with('category')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
            
        // Get recent inventory activities (combining both stock-in and stock-out/adjustments)
        $recentStockAdjustments = StockAdjustment::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($adjustment) {
                return [
                    'id' => $adjustment->id,
                    'reference_number' => $adjustment->reference_number,
                    'date' => $adjustment->created_at,
                    'type' => $adjustment->type,
                    'items_count' => $adjustment->items->count(),
                    'user_name' => $adjustment->user->name ?? 'Unknown',
                    'notes' => $adjustment->notes
                ];
            });

        $recentStockins = Stockin::with(['supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($stockin) {
                return [
                    'id' => $stockin->id,
                    'reference_number' => $stockin->invoice_number ?? ('SI-' . $stockin->id),
                    'date' => $stockin->created_at,
                    'type' => 'stock_in',
                    'items_count' => $stockin->items->count(),
                    'user_name' => $stockin->user->name ?? 'System',
                    'notes' => $stockin->notes,
                    'supplier' => $stockin->supplier->name ?? 'Unknown Supplier'
                ];
            });

        // Merge and sort activities by date
        $recentActivities = $recentStockAdjustments->concat($recentStockins)
            ->sortByDesc('date')
            ->take(5);

        // Calculate inventory statistics
        $inventoryValue = Product::sum(DB::raw('stock * price'));
        $normalStockCount = Product::whereRaw('stock > reorder_level')->count();
        $lowStockCount = Product::whereRaw('stock <= reorder_level AND stock > 0')->count();
        $criticalStockCount = Product::where('stock', 0)->count();
            
        return view('inventory.index', compact(
            'lowStockProducts', 
            'recentActivities',
            'inventoryValue', 
            'normalStockCount', 
            'lowStockCount', 
            'criticalStockCount'
        ));
    }
} 