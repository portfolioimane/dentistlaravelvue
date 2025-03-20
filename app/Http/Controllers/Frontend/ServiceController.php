<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    // Fetch all services
    public function index()
    {
        // Fetch all services
        $services = Service::all();

        // Log the services data
        Log::info('Services fetched: ', $services->toArray());

        // Return services data as JSON
        return response()->json($services);
    }

    // Fetch a specific service by ID
    public function show($id)
    {
        // Fetch the service by ID
        $service = Service::findOrFail($id);

        // Log the service data
        Log::info('Service fetched: ', $service->toArray());

        // Return the service data as JSON
        return response()->json($service);
    }

    // Fetch featured services (featured flag in Service model)
    public function getFeaturedServices()
    {
        // Fetch the featured services
        $featuredServices = Service::where('featured', true)
            ->latest()
            ->take(4)  // Get the latest 4 featured services
            ->get();

        // Log the featured services data
        Log::info('Featured services fetched: ', $featuredServices->toArray());

        // Return the featured services data as JSON
        return response()->json($featuredServices, 200);
    }

}
