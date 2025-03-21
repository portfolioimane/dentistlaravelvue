<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    // Fetch all users
    public function index()
    {
        $users = User::all(); // Get all users
        return response()->json($users);
    }

    // Fetch current logged-in user
    public function currentUser(Request $request)
    {
        return response()->json($request->user()); // Return the current logged-in user
    }

    // Fetch customers (only users with 'customer' role)
public function customers()
{
    // Get users (patients) who have bookings with status 'completed'
    $customers = User::where('role', 'customer')
                     ->whereHas('bookings', function ($query) {
                         $query->where('status', 'completed'); // Assuming 'status' column exists in bookings table
                     })
                     ->get();

    return response()->json($customers);
}


    // Add a new user
    public function store(Request $request)
{
    // Validate incoming request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:admin,customer',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'phone' => 'nullable|string|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Create a new user
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->role = $request->role;
    
    // Store the avatar path as 'storage/avatars/filename'
    $user->avatar = $request->file('avatar') 
        ? 'storage/' . $request->file('avatar')->store('avatars', 'public') 
        : null;
    
    $user->phone = $request->phone;
    $user->save();

    return response()->json($user, 201);
}

public function update(Request $request, $id)
{
    // Validate incoming request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:admin,customer',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'phone' => 'nullable|string|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Find the user by ID
    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    
    // Store the avatar path as 'storage/avatars/filename'
    $user->avatar = $request->file('avatar') 
        ? 'storage/' . $request->file('avatar')->store('avatars', 'public') 
        : $user->avatar;
    
    $user->phone = $request->phone;
    $user->save();

    return response()->json($user);
}


    // Delete a user
    public function destroy($id)
    {
        // Find the user by ID and delete
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
