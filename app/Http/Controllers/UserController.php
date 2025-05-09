<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $role = $request->input('role');
            
            $query = User::query()
                ->with(['creator', 'updater']); // Eager load creator and updater
            
            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Apply role filter
            if ($role) {
                $query->where('role', $role);
            }
            
            $users = $query->orderBy('created_at', 'desc')
                          ->paginate(10);
            
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@index: ' . $e->getMessage());
            return back()->with('error', 'Error loading users: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'string', 'in:admin,staff'],
                'is_active' => ['required', 'boolean'],
                'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            ]);

            Log::info('Creating new user with data:', ['email' => $validated['email'], 'role' => $validated['role']]);

            DB::beginTransaction();

            $currentUser = Auth::id();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'birthdate' => $validated['birthdate'] ?? null,
                'created_by' => $currentUser,
                'updated_by' => $currentUser,
            ]);

            DB::commit();

            Log::info('User created successfully:', ['id' => $user->id, 'email' => $user->email]);

            return redirect()->route('users.index')
                           ->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in UserController@store:', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in UserController@store: ' . $e->getMessage());
            return back()->with('error', 'Error creating user: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Get user data for editing
     */
    public function edit(User $user)
    {
        try {
            // Load creator and updater details if needed
            $user->load(['creator', 'updater']);
            return response()->json($user);
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading user data'], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        try {
            // Check if user is trying to modify their own admin status
            if (Auth::id() === $user->id && 
                $user->isAdmin() && 
                $request->input('role') !== 'admin') {
                return back()->with('error', 'You cannot remove your own admin status.')
                            ->withInput();
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'role' => ['required', 'string', 'in:admin,staff'],
                'is_active' => ['required', 'boolean'],
                'password' => $request->filled('password') ? ['confirmed', Rules\Password::defaults()] : [],
                'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            ]);

            Log::info('Updating user:', ['id' => $user->id, 'email' => $validated['email']]);

            DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'birthdate' => $validated['birthdate'] ?? null,
                'updated_by' => Auth::id(),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            Log::info('User updated successfully:', ['id' => $user->id]);

            return redirect()->route('users.index')
                           ->with('success', 'User updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in UserController@update:', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in UserController@update: ' . $e->getMessage());
            return back()->with('error', 'Error updating user: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Delete the specified user
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deletion of your own account
            if (Auth::id() === $user->id) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            Log::info('Deleting user:', ['id' => $user->id, 'email' => $user->email]);

            DB::beginTransaction();
            $user->delete();
            DB::commit();

            Log::info('User deleted successfully:', ['id' => $user->id]);

            return redirect()->route('users.index')
                           ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in UserController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        try {
            // Check if user is trying to deactivate their own account
            if (Auth::id() === $user->id) {
                return back()->with('error', 'You cannot change your own account status.');
            }

            $newStatus = !$user->is_active;
            $statusText = $newStatus ? 'activated' : 'deactivated';

            Log::info('Toggling user status:', [
                'id' => $user->id, 
                'email' => $user->email, 
                'new_status' => $statusText
            ]);

            DB::beginTransaction();

            $user->update([
                'is_active' => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            Log::info("User {$statusText} successfully:", ['id' => $user->id]);

            return redirect()->route('users.index')
                          ->with('success', "User {$statusText} successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in UserController@toggleStatus: ' . $e->getMessage());
            return back()->with('error', "Error changing user status: " . $e->getMessage());
        }
    }
} 