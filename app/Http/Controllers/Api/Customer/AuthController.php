<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Ensure Auth is imported
use Illuminate\Support\Facades\Storage; // Add this for file storage
use App\Models\Cart; // Ensure you have a Cart model
use App\Models\CartItem; // Ensure you have a CartItem model

class AuthController extends Controller
{

       public function getUser(Request $request)
    {
        return response()->json([
            'user' => Auth::user(), // Return the authenticated user
        ]);
    }

    public function register(Request $request)
    {
        // Log the incoming request data
        Log::info('Registration attempt', ['request' => $request->all()]);

        // Validate user input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Remove the role from the validation rules
            // 'role' => 'required|string|in:admin,customer', // Commented out or removed
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create user
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // Set default role to 'customer' if not specified
                'role' => 'customer', // Default role
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'User registration failed'], 500);
        }

        // Do not generate token here, instead, return user details
        return response()->json(['user' => $user], 201);
    }

public function login(Request $request)
{
    // Log the incoming request for debugging
    Log::info('Login attempt received', ['email' => $request->email]);

    // Validate user input
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        Log::warning('Validation failed', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Check credentials
    $user = User::where('email', $request->email)->first();
    Log::info('Checking user credentials', ['email' => $request->email, 'user_found' => $user !== null]);

    if (!$user || !Hash::check($request->password, $user->password)) {
        Log::warning('Invalid credentials', ['email' => $request->email]);
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate token
    $token = $user->createToken('API Token')->plainTextToken;
    Log::info('Generated token for user', ['user_id' => $user->id, 'token' => $token]);

    // Fetch session_id from headers instead of request body
    $session_id = $request->header('X-Session-ID'); // Retrieve session_id from headers
    Log::info('Session ID received from headers', ['session_id' => $session_id]);

    if ($session_id) {
        // Retrieve the session-based cart using session_id
        $sessionCart = Cart::where('session_id', $session_id)->first();
        Log::info('Session cart retrieval attempt', ['session_cart_found' => $sessionCart !== null, 'session_cart_id' => $sessionCart ? $sessionCart->id : null]);

        if ($sessionCart) {
            // Retrieve or create a user-specific cart
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
            Log::info('User cart retrieved or created', ['user_cart_id' => $userCart->id]);

            // Transfer items from the session cart to the user's cart
            foreach ($sessionCart->cartItems as $item) {
                Log::info('Processing item from session cart', ['product_id' => $item->product_id, 'quantity' => $item->quantity]);

                // Check if the item already exists in the user's cart
                $existingItem = $userCart->cartItems()->where('product_id', $item->product_id)->first();

                if ($existingItem) {
                    // If the item exists, update the quantity
                    Log::info('Item exists in user cart. Updating quantity', ['product_id' => $item->product_id, 'existing_quantity' => $existingItem->quantity, 'added_quantity' => $item->quantity]);

                    $existingItem->quantity += $item->quantity;
                    $existingItem->save();
                } else {
                    // If the item does not exist, create a new cart item
                    Log::info('Item does not exist in user cart. Creating new item', ['product_id' => $item->product_id, 'quantity' => $item->quantity]);

                    $userCart->cartItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                }
            }

            // Remove the session cart after transferring items
            Log::info('Deleting session cart after transferring items', ['session_cart_id' => $sessionCart->id]);
            $sessionCart->delete();
        } else {
            Log::warning('No session cart found for session_id', ['session_id' => $session_id]);
        }
    }

    // Log the final response
    Log::info('Login successful', ['user_id' => $user->id, 'email' => $user->email]);

    // Return response with token and user information
    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role, // Include role information
        ]
    ]);
}

public function updateProfile(Request $request)
{
    $user = Auth::user(); // Get the authenticated user
    Log::debug('Updating profile for user ID: ' . $user->id); // Log user ID

    // Validate the input data
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255', // Allow name to be optional (nullable)
        'email' => 'nullable|email|unique:users,email,' . $user->id, // Email is nullable but must be unique
        'phone' => 'nullable|string|max:15',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation for avatar
        'password' => 'nullable|string|min:6|confirmed', // Password confirmation validation
    ]);

    // Check if the user has uploaded a new avatar file
    if ($request->hasFile('avatar')) {
        Log::debug('Avatar file uploaded'); // Log file upload event

        // Delete the old avatar if it exists
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar); // Delete old avatar
            Log::debug('Deleted old avatar: ' . $user->avatar); // Log file deletion
        }

        // Store the new avatar file and get the full path
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
        // Save the full avatar path in the database, like 'storage/avatars/imagename.jpg'
        $validatedData['avatar'] = 'storage/' . $avatarPath;
    }

    // Check if the password is being updated and hash it
    if ($request->filled('password')) {
        $validatedData['password'] = bcrypt($request->password); // Hash the password before saving
    }

    // Update the user profile
    try {
        $user->update($validatedData); // Update user information
        Log::debug('Profile updated for user ID: ' . $user->id); // Log successful update
        return response()->json(['message' => 'Profile updated successfully']);
    } catch (\Exception $e) {
        Log::error('Failed to update profile', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to update profile', 'details' => $e->getMessage()], 500);
    }
}




}