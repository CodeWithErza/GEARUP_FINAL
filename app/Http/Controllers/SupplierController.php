<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('stockins')
            ->orderBy('name')
            ->paginate(10);
            
        // Add product count for each supplier
        $suppliers->each(function ($supplier) {
            $supplier->product_count = $supplier->getProductCountAttribute();
        });
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'payment_terms' => 'required|in:cod,15days,30days,60days',
            'status' => 'required|in:active,on_hold,inactive',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier = Supplier::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier added successfully',
            'supplier' => $supplier
        ]);
    }

    public function show(Supplier $supplier)
    {
        $supplier->product_count = $supplier->getProductCountAttribute();
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'payment_terms' => 'required|in:cod,15days,30days,60days',
            'status' => 'required|in:active,on_hold,inactive',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $suppliers = Supplier::where('name', 'like', "%{$query}%")
            ->orWhere('contact_person', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('supplier_code', 'like', "%{$query}%")
            ->withCount('stockins')
            ->orderBy('name')
            ->get();
            
        // Add product count for each supplier
        $suppliers->each(function ($supplier) {
            $supplier->product_count = $supplier->getProductCountAttribute();
        });
            
        return response()->json($suppliers);
    }
    
    public function getAllSuppliers()
    {
        $suppliers = Supplier::withCount('stockins')->get();
        
        // Add product count for each supplier
        $suppliers->each(function ($supplier) {
            $supplier->product_count = $supplier->getProductCountAttribute();
        });
        
        return response()->json($suppliers);
    }
} 