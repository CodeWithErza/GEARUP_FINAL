<?php

namespace App\Http\Controllers;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    /**
     * Constructor that excludes the store method from CSRF verification
     */
    public function __construct()
    {
        $this->middleware('web')->except('store');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'date' => 'required|date',
            'transaction_type' => 'required|in:stock_out,adjustment',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.new_count' => 'required|numeric|min:0',
            'items.*.reason' => 'required|string|in:damaged,expired,lost,other,count',
        ]);

        try {
            DB::beginTransaction();

            // Create stock adjustment record
            $stockAdjustment = StockAdjustment::create([
                'reference_number' => $validated['reference_number'],
                'date' => $validated['date'],
                'type' => $validated['transaction_type'],
                'notes' => $validated['notes'],
                'processed_by' => auth()->id()
            ]);

            // Process each item
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $currentStock = $product->stock;
                $newCount = $item['new_count'];
                
                // Calculate difference based on transaction type
                $difference = $validated['transaction_type'] === 'adjustment' 
                    ? $newCount - $currentStock 
                    : -$newCount;

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
                'message' => ucfirst($validated['transaction_type']) . ' processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing ' . $validated['transaction_type'] . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $adjustments = StockAdjustment::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('inventory.stock-adjustments', compact('adjustments'));
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        return response()->json($stockAdjustment->load(['items.product', 'user']));
    }
}
