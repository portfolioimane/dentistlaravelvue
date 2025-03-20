<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    public function index()
    {
        // Fetch all services
        $services = Service::all();
        Log::info('Fetched all services', ['services' => $services]);
        return response()->json($services);
    }

    public function store(Request $request)
    {
        // Validate and create a new service
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'duration' => 'required|integer', // Duration in minutes
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Store image if uploaded
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/services', 'public');
            $data['image'] = '/storage/' . $path;
            Log::info('Service image uploaded', ['path' => $path]);
        }

        // Create the service
        $service = Service::create($data);
        Log::info('New service created', ['service' => $service]);
        return response()->json($service, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate and update a service
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'sometimes|required|numeric',
            'duration' => 'sometimes|required|integer', // Duration in minutes
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed on update', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::findOrFail($id);
        $data = $request->all();

        // Store new image if uploaded, and delete the old one
        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::delete('public/' . $service->image);
            }
            $path = $request->file('image')->store('images/services', 'public');
            $data['image'] = '/storage/' . $path;
        }

        // Update the service
        $service->update($data);
        Log::info('Service updated', ['service' => $service]);
        return response()->json($service);
    }

    public function show($id)
    {
        // Fetch a single service by ID
        $service = Service::findOrFail($id);
        Log::info('Fetched service by ID', ['service' => $service]);
        return response()->json($service);
    }

    public function destroy($id)
    {
        // Delete service and image if exists
        $service = Service::findOrFail($id);
        if ($service->image) {
            Storage::delete('public/' . $service->image);
        }
        $service->delete();
        Log::info('Service deleted', ['service_id' => $id]);
        return response()->json(null, 204);
    }

    public function toggleFeatured($serviceId)
    {
        // Find the service by ID
        $service = Service::findOrFail($serviceId);

        // Toggle the featured status
        $service->featured = !$service->featured;

        // Save the service with the updated featured status
        $service->save();

        // Return the updated service as a JSON response
        return response()->json($service);
    }
}
