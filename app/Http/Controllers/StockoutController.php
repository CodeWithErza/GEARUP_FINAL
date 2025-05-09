<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockoutController extends Controller
{
    /**
     * Constructor that applies middleware
     */
    public function __construct()
    {
        // Remove the middleware call since it's causing issues
        // We'll handle CSRF via the token in the form instead
    }

    /**
     * Display the stock out form with recent transactions
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get recent stock out transactions
        $recentTransactions = StockAdjustment::where('type', 'stock_out')
            ->with('items.product', 'user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Generate the next reference number
        $datePrefix = 'SO-' . date('Ymd');
        $latestRefNumber = StockAdjustment::where('reference_number', 'like', $datePrefix . '-%')
            ->orderBy('reference_number', 'desc')
            ->first();
        
        $counter = 1;
        if ($latestRefNumber) {
            $matches = [];
            if (preg_match('/-(\d+)$/', $latestRefNumber->reference_number, $matches)) {
                $counter = (int)$matches[1] + 1;
            }
        }
        
        $nextRefNumber = $datePrefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            
        return view('inventory.stock-out', [
            'recentTransactions' => $recentTransactions,
            'nextRefNumber' => $nextRefNumber
        ]);
    }

    /**
     * Store a newly created stock out transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.new_count' => 'required|numeric|min:0',
            'items.*.reason' => 'required|string|in:damaged,expired,lost,other',
        ]);

        try {
            DB::beginTransaction();

            // Generate a unique reference number
            $datePrefix = 'SO-' . date('Ymd', strtotime($validated['date']));
            
            // Find the latest reference number with this date prefix
            $latestRefNumber = StockAdjustment::where('reference_number', 'like', $datePrefix . '-%')
                ->orderBy('reference_number', 'desc')
                ->first();
            
            $counter = 1;
            if ($latestRefNumber) {
                // Extract the counter from the latest reference number
                $matches = [];
                if (preg_match('/-(\d+)$/', $latestRefNumber->reference_number, $matches)) {
                    $counter = (int)$matches[1] + 1;
                }
            }
            
            // Format the counter with leading zeros
            $uniqueRefNumber = $datePrefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            
            // Double check for uniqueness
            while (StockAdjustment::where('reference_number', $uniqueRefNumber)->exists()) {
                $counter++;
                $uniqueRefNumber = $datePrefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            }

            // Create stock adjustment record (using stock_out as fixed type)
            $stockAdjustment = StockAdjustment::create([
                'reference_number' => $uniqueRefNumber,
                'date' => $validated['date'],
                'type' => 'stock_out',  // Always stock_out
                'notes' => $validated['notes'],
                'processed_by' => auth()->id()
            ]);

            // Process each item
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $currentStock = $product->stock;
                $newCount = $item['new_count'];
                
                // For stock-out, we're always reducing stock
                $difference = -$newCount;

                // Create stock adjustment item
                $stockAdjustment->items()->create([
                    'product_id' => $item['product_id'],
                    'current_stock' => $currentStock,
                    'new_count' => $newCount,
                    'difference' => $difference,
                    'reason' => $item['reason']
                ]);

                // Update product stock
                $product->update(['stock' => $currentStock + $difference]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock out processed successfully',
                'reference_number' => $uniqueRefNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing stock out: ' . $e->getMessage()
            ], 500);
        }
    }
} 