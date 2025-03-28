<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Import Str facade for password generation

class CustomersController extends Controller
{
    public function index()
    {
        // Fetch all customers
        $customers = User::where('role', 'customer')->get();
        Log::info('Fetched all customers', ['customers' => $customers]);
        return response()->json($customers);
    }


public function store(Request $request)
{
    // Validate and create a new customer
$validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'phone' => 'nullable|string|max:15',
    'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    'date_of_birth' => 'nullable|date',
    'gender' => 'nullable|in:male,female,other',
    'emergency_contact' => 'nullable|string|max:255',
    'medical_history' => 'nullable|string',
    'insurance_provider' => 'nullable|string|max:255',
    'insurance_id' => 'nullable|string|max:255',
    'is_insured' => 'nullable|boolean',  // Add this line for is_insured
]);


    if ($validator->fails()) {
        Log::error('Validation failed', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }
    Log::info('Request data', ['data' => $request->all()]);


    $data = $request->all();

    // Generate a random password
    $data['password'] = bcrypt(Str::random(12)); // Generate a 12-character password and hash it

    // Store avatar if uploaded
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = '/storage/' . $path;
        Log::info('Customer avatar uploaded', ['path' => $path]);
    }

    // Create the customer
    $customer = User::create($data);
    Log::info('New customer created', ['customer' => $customer]);
    return response()->json($customer, 201);
}

public function update(Request $request, $id)
{
    // Validate and update a customer
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $id,
        'phone' => 'nullable|string|max:15',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'emergency_contact' => 'nullable|string|max:255',
        'medical_history' => 'nullable|string',
        'is_insured' => 'nullable|boolean',
        'insurance_provider' => 'nullable|string|max:255',
        'insurance_id' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed on update', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $customer = User::findOrFail($id);
    $data = $request->all();

    // If 'is_insured' is 0 (No), make insurance fields nullable (null)
    if (isset($data['is_insured']) && $data['is_insured'] == 0) {
        $data['insurance_provider'] = null;
        $data['insurance_id'] = null;
    }

    // Store new avatar if uploaded, and delete the old one
    if ($request->hasFile('avatar')) {
        if ($customer->avatar) {
            Storage::delete('public/' . $customer->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = '/storage/' . $path;
    }

    // Update the customer
    $customer->update($data);

    Log::info('Customer updated', ['customer' => $customer]);
    return response()->json($customer);
}


    public function show($id)
    {
        // Fetch a single customer by ID
        $customer = User::findOrFail($id);
        Log::info('Fetched customer by ID', ['customer' => $customer]);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        // Delete customer and avatar if exists
        $customer = User::findOrFail($id);
        if ($customer->avatar) {
            Storage::delete('public/' . $customer->avatar);
        }
        $customer->delete();
        Log::info('Customer deleted', ['customer_id' => $id]);
        return response()->json(null, 204);
    }
}
