<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OrderHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function getProducts()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => $product->category->name,
                    'stock' => $product->stock,
                    'sku' => $product->sku,
                    'image' => $product->image ? asset($product->image) : asset('images/product_placeholder.jpg'),
                    'brand' => $product->brand,
                    'description' => $product->description
                ];
            });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer.name' => 'required|string|max:255',
            'customer.phone' => 'nullable|string|max:20',
            'customer.email' => 'nullable|email|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'payment.method' => 'required|in:cash,gcash,maya',
            'payment.amount_received' => 'required|numeric|min:0',
            'payment.reference' => 'nullable|string|max:255',
            'summary.subtotal' => 'required|numeric|min:0',
            'summary.tax' => 'required|numeric|min:0',
            'summary.discount_type' => 'required|in:amount,percent',
            'summary.discount_value' => 'required|numeric|min:0',
            'summary.total' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['name' => $request->input('customer.name')],
                [
                    'phone' => $request->input('customer.phone'),
                    'email' => $request->input('customer.email')
                ]
            );

            // Calculate discount amount based on type
            $discountAmount = 0;
            $discountPercentage = 0;
            if ($request->input('summary.discount_type') === 'amount') {
                $discountAmount = $request->input('summary.discount_value');
            } else {
                $discountPercentage = $request->input('summary.discount_value');
                $discountAmount = ($request->input('summary.subtotal') * $discountPercentage) / 100;
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 3, '0', STR_PAD_LEFT),
                'subtotal' => (float) $request->input('summary.subtotal'),
                'tax' => (float) $request->input('summary.tax'),
                'discount_amount' => (float) $discountAmount,
                'discount_percentage' => (float) $discountPercentage,
                'total' => (float) $request->input('summary.total'),
                'payment_method' => $request->input('payment.method'),
                'amount_received' => (float) $request->input('payment.amount_received'),
                'change' => $request->input('payment.method') === 'cash' 
                    ? (float) ($request->input('payment.amount_received') - $request->input('summary.total'))
                    : null,
                'payment_reference' => $request->input('payment.reference'),
                'notes' => $request->input('notes'),
                'status' => 'completed'
            ]);

            // Create order items
            foreach ($request->input('items') as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchBarcode(Request $request)
    {
        $product = Product::where('sku', $request->barcode)
            ->orWhere('id', $request->barcode)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'sku' => $product->sku
            ]
        ]);
    }

    /**
     * Print order receipt
     */
    public function printReceipt(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('orders.receipt', compact('order'));
    }

    /**
     * Display the order history page
     */
    public function history()
    {
        return view('orders.order_history');
    }

    /**
     * Display the order edit page
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('orders.edit', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        try {
            // Check if order can be cancelled
            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is already cancelled'
                ], 400);
            }

            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->save();

            // Restore product stock
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an order
     */
    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Update order status and payment method
            $order->status = $request->status;
            $order->payment_method = $request->payment_method;
            
            // Handle order items
            $subtotal = 0;
            $updatedItems = [];
            
            foreach ($request->items as $itemId => $itemData) {
                // Check if item is marked for deletion
                if (isset($itemData['_delete']) && $itemData['_delete'] == 1) {
                    // Get the item
                    $orderItem = OrderItem::find($itemId);
                    
                    // Restore product stock
                    $product = $orderItem->product;
                    $product->increment('stock', $orderItem->quantity);
                    
                    // Delete the item
                    $orderItem->delete();
                    continue;
                }
                
                // Update item quantity
                $orderItem = OrderItem::find($itemId);
                $oldQuantity = $orderItem->quantity;
                $newQuantity = $itemData['quantity'];
                
                // Update stock if quantity changed
                if ($oldQuantity != $newQuantity) {
                    $product = $orderItem->product;
                    $stockDiff = $oldQuantity - $newQuantity;
                    
                    if ($stockDiff > 0) {
                        // Returning items to stock
                        $product->increment('stock', $stockDiff);
                    } else {
                        // Removing items from stock
                        $product->decrement('stock', abs($stockDiff));
                    }
                    
                    // Update item quantity and subtotal
                    $orderItem->quantity = $newQuantity;
                    $orderItem->subtotal = $orderItem->price * $newQuantity;
                    $orderItem->save();
                }
                
                $subtotal += $orderItem->subtotal;
                $updatedItems[] = $orderItem->id;
            }
            
            // Calculate tax and total
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;
            
            // Update order totals
            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->total = $total;
            $order->save();
            
            DB::commit();
            
            return redirect()->route('orders.history')
                ->with('success', 'Order updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export order history in the specified format
     */
    public function exportHistory($format)
    {
        $query = Order::with(['customer', 'items.product'])
            ->when(request('date_range'), function ($q) {
                switch (request('date_range')) {
                    case 'today':
                        return $q->whereDate('created_at', now());
                    case 'yesterday':
                        return $q->whereDate('created_at', now()->subDay());
                    case 'this_week':
                        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    case 'last_week':
                        return $q->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    case 'this_month':
                        return $q->whereMonth('created_at', now()->month);
                    case 'last_month':
                        return $q->whereMonth('created_at', now()->subMonth()->month);
                    case 'this_year':
                        return $q->whereYear('created_at', now()->year);
                    case 'custom':
                        if (request('start_date') && request('end_date')) {
                            return $q->whereBetween('created_at', [request('start_date'), request('end_date')]);
                        }
                        return $q;
                }
            })
            ->when(request('order_status') && request('order_status') !== 'all', function ($q) {
                return $q->where('status', request('order_status'));
            })
            ->when(request('search'), function ($q) {
                return $q->where(function ($query) {
                    $query->where('order_number', 'like', "%" . request('search') . "%")
                        ->orWhereHas('customer', function ($q) {
                            $q->where('name', 'like', "%" . request('search') . "%");
                        });
                });
            });

        // Apply sorting
        if (request('sort_by')) {
            switch (request('sort_by')) {
                case 'order_number':
                    $query->orderBy('order_number', 'asc');
                    break;
                case 'date_newest':
                    $query->latest();
                    break;
                case 'date_oldest':
                    $query->oldest();
                    break;
                case 'amount_high':
                    $query->orderBy('total', 'desc');
                    break;
                case 'amount_low':
                    $query->orderBy('total', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        if ($format === 'pdf') {
            $orders = $query->get();
            $pdf = PDF::loadView('orders.pdf_history', [
                'orders' => $orders,
                'dateRange' => request('date_range'),
                'orderStatus' => request('order_status'),
                'search' => request('search'),
                'sortBy' => request('sort_by')
            ]);

            return $pdf->download('order_history.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new OrderHistoryExport($query), 'order_history.xlsx');
        }

        return response()->json(['message' => 'Export format not supported'], 400);
    }

    public function getDashboardData()
    {
        $today = now()->startOfDay();
        
        // Get today's sales
        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');
            
        // Get today's orders count
        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();
            
        // Get recent orders
        $recentOrders = Order::with(['customer', 'items'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->format('F d, Y'),
                    'items_count' => $order->items->count(),
                    'total' => $order->total,
                    'status' => $order->status,
                    'customer_name' => $order->customer->name
                ];
            });
            
        // Get top products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->with('product:id,name,image')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity,
                    'total_sales' => $item->total_sales,
                    'image' => $item->product->image ? asset($item->product->image) : asset('images/product_placeholder.jpg')
                ];
            });
            
        return response()->json([
            'today_sales' => $todaySales,
            'today_orders' => $todayOrders,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts
        ]);
    }

    public function getOrderHistory(Request $request)
    {
        $query = Order::with(['customer', 'items'])
            ->when($request->date_range, function ($q) use ($request) {
                switch ($request->date_range) {
                    case 'today':
                        return $q->whereDate('created_at', now());
                    case 'yesterday':
                        return $q->whereDate('created_at', now()->subDay());
                    case 'this_week':
                        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    case 'last_week':
                        return $q->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    case 'this_month':
                        return $q->whereMonth('created_at', now()->month);
                    case 'last_month':
                        return $q->whereMonth('created_at', now()->subMonth()->month);
                    case 'this_year':
                        return $q->whereYear('created_at', now()->year);
                    case 'custom':
                        if ($request->start_date && $request->end_date) {
                            return $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
                        }
                        return $q;
                }
            })
            ->when($request->order_status && $request->order_status !== 'all', function ($q) use ($request) {
                return $q->where('status', $request->order_status);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('order_number', 'like', "%{$request->search}%")
                        ->orWhereHas('customer', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                });
            });
            
        // Apply sorting based on sort_by parameter
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'order_number':
                    $query->orderBy('order_number', 'asc');
                    break;
                case 'date_newest':
                    $query->latest();
                    break;
                case 'date_oldest':
                    $query->oldest();
                    break;
                case 'amount_high':
                    $query->orderBy('total', 'desc');
                    break;
                case 'amount_low':
                    $query->orderBy('total', 'asc');
                    break;
                default:
                    $query->latest(); // Default sort if no valid option provided
                    break;
            }
        } else {
            $query->latest(); // Default sort if no sort parameter
        }
            
        $orders = $query->paginate(10);
        
        return response()->json($orders);
    }

    /**
     * Get detailed order information
     */
    public function getOrderDetails(Order $order)
    {
        $order->load(['customer', 'items.product']);
        
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'total' => $order->total,
                'payment_method' => $order->payment_method,
                'amount_received' => $order->amount_received,
                'change_amount' => $order->amount_received - $order->total,
                'customer' => [
                    'name' => $order->customer->name,
                    'email' => $order->customer->email,
                    'phone' => $order->customer->phone,
                ],
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'sku' => $item->product->sku,
                        ],
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
            ]
        ]);
    }
} 