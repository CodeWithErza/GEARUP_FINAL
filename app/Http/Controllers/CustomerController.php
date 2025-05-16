<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return response()->json($customers);
    }

    /**
     * Search for customers based on a query.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }
        
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();
            
        return response()->json($customers);
    }
} 