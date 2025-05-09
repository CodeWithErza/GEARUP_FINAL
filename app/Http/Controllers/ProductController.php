<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withTrashed()
            ->with('category')
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(10);
        $categories = Category::all();
        $lowStockProducts = Product::whereRaw('stock <= reorder_level')->get();
        $totalProducts = Product::withTrashed()->count();
        
        return view('products.index', compact('products', 'categories', 'lowStockProducts', 'totalProducts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productData = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $productData['image'] = 'images/products/' . $imageName;
        } else {
            $productData['image'] = 'images/product_placeholder.jpg';
        }

        $product = Product::create($productData);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added successfully',
            'product' => $product->load('category')
        ]);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productData = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Remove old image if not the placeholder
            if ($product->image && $product->image != 'images/product_placeholder.jpg') {
                if (file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $productData['image'] = 'images/products/' . $imageName;
        }

        $product->update($productData);
        
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => $product->load('category')
        ]);
    }

    public function destroy(Product $product)
    {
        try {
            // Mark the product as inactive and soft delete it
            $product->update(['is_active' => false]);
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product marked as inactive'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark product as inactive',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $categoryId = $request->get('category_id');
        
        $products = Product::withTrashed()
            ->with('category')
            ->when($query, function($q) use ($query) {
                return $q->where(function($subq) use ($query) {
                    $subq->where('name', 'like', "%{$query}%")
                        ->orWhere('sku', 'like', "%{$query}%")
                        ->orWhere('brand', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, function($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();
            
        return response()->json($products);
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->whereRaw('stock <= reorder_level')
            ->orderBy('stock')
            ->get();
            
        return response()->json($products);
    }

    public function updateStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'operation' => 'required|in:add,subtract'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentStock = $product->stock;
        $newStock = $request->operation === 'add' 
            ? $currentStock + $request->stock 
            : $currentStock - $request->stock;

        if ($newStock < 0) {
            return response()->json([
                'error' => 'Insufficient stock'
            ], 422);
        }

        $product->update(['stock' => $newStock]);
        
        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'product' => $product->load('category')
        ]);
    }

    /**
     * API endpoint to get all products for the modal
     */
    public function apiIndex()
    {
        $products = Product::with('category')
            ->select('id', 'name', 'sku', 'stock', 'category_id')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'stock' => $product->stock,
                    'category' => $product->category ? $product->category->name : 'Uncategorized'
                ];
            });
            
        return response()->json($products);
    }

    /**
     * API endpoint to search products for the modal
     */
    public function apiSearch(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::with('category')
            ->select('id', 'name', 'sku', 'stock', 'category_id')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'stock' => $product->stock,
                    'category' => $product->category ? $product->category->name : 'Uncategorized'
                ];
            });
            
        return response()->json($products);
    }

    /**
     * Toggle the active status of a product
     */
    public function toggleActive(Request $request, Product $product)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_active' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            if ($request->is_active) {
                // If activating, restore if it was soft deleted
                if ($product->trashed()) {
                    $product->restore();
                }
            }

            $product->update(['is_active' => $request->is_active]);
            
            $status = $request->is_active ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "Product {$status} successfully",
                'product' => $product->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to {$status} product",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted product
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        $product->update(['is_active' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully',
            'product' => $product->load('category')
        ]);
    }
} 