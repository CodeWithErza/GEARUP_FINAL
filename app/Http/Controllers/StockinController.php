<?php

namespace App\Http\Controllers;

use App\Models\Stockin;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StockinController extends Controller
{
    public function index()
    {
        $stockins = Stockin::with(['supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $suppliers = Supplier::where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();
            
        return view('inventory.stock-in', compact('stockins', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        // Debug the incoming request
        Log::info('Stock-in request received:', [
            'request_data' => $request->all()
        ]);

        // Decode JSON items string to array
        $items = json_decode($request->items, true);
        if (!$items) {
            Log::error('Failed to decode items JSON', [
                'items_raw' => $request->items
            ]);
            return back()->with('error', 'Invalid item data format')->withInput();
        }

        Log::info('Items decoded successfully:', ['items' => $items]);
        $request->merge(['items' => $items]);

        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $totalAmount = collect($items)->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $stockin = Stockin::create([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'completed',
                'created_by' => auth()->id()
            ]);

            Log::info('Stockin record created:', ['stockin_id' => $stockin->id]);

            foreach ($items as $item) {
                $stockin->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price']
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                if ($product) {
                    $oldStock = $product->stock;
                    $product->increment('stock', $item['quantity']);
                    Log::info('Product stock updated:', [
                        'product_id' => $product->id, 
                        'old_stock' => $oldStock,
                        'increment' => $item['quantity'],
                        'new_stock' => $product->stock
                    ]);
                } else {
                    Log::error('Product not found for updating stock', ['product_id' => $item['product_id']]);
                }
            }

            DB::commit();
            Log::info('Stock-in processed successfully');

            return redirect()
                ->route('inventory')
                ->with('success', 'Stock in completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock-in processing error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->with('error', 'Error processing stock in: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Stockin $stockin)
    {
        return response()->json($stockin->load(['supplier', 'items.product']));
    }

    public function destroy(Stockin $stockin)
    {
        if ($stockin->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a completed stock in'
            ], 422);
        }

        $stockin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock in deleted successfully'
        ]);
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'supplier_code']);
            
        return response()->json($suppliers);
    }

    public function getProducts()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'stock']);
            
        return response()->json($products);
    }
} 