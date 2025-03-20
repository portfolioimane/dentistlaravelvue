<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    // Fetch the latest three featured reviews with the user and service relationships
    public function latestFeaturedReviews()
    {
        // Fetch only featured reviews, ordered by the most recent, with user and service relationships eager-loaded
        $reviews = Review::with(['user', 'service']) // Eager load user and service relationships
                         ->where('is_featured', true) // Only get featured reviews
                         ->latest() // Order by created_at in descending order (latest)
                         ->take(3) // Limit to the latest three reviews
                         ->get();

        // Return the reviews as JSON
        return response()->json($reviews);
    }

    // Fetch reviews for a specific service
    public function index($serviceId)
    {
        // Fetch only approved reviews for the given serviceId
        $reviews = Review::with('user', 'service') // Eager load the user and service relationships
                         ->where('service_id', $serviceId)
                         ->where('status', 'approved') // Filter reviews by approved status
                         ->get();

        // Return reviews as JSON
        return response()->json($reviews);
    }

    // Submit a review for a specific service
    public function store(Request $request, $serviceId)
    {
        $validatedData = $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        Log::info('Review validation passed.', ['validated_data' => $validatedData]);

        // Find the service
        try {
            $service = Service::findOrFail($serviceId);
            Log::info('Service found.', ['service_id' => $serviceId]);
        } catch (\Exception $e) {
            Log::error('Service not found.', ['service_id' => $serviceId, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Service not found.'], 404);
        }

        // Create the new review
        $review = new Review([
            'review' => $request->review,
            'rating' => $request->rating,
            'user_id' => auth()->id(), // Assuming the user is authenticated
        ]);

        Log::info('Review created.', [
            'user_id' => auth()->id(),
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        // Save the review and associate it with the service via the relationship
        try {
            $service->reviews()->save($review);
            Log::info('Review saved and associated with the service.', ['service_id' => $serviceId, 'review_id' => $review->id]);
        } catch (\Exception $e) {
            Log::error('Failed to save review.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save review.'], 500);
        }

        // Eager load the user and service relationships
        $review = $review->load('user', 'service');  // Load both 'user' and 'service' relationships

        // Return the review with associated user and service
        return response()->json($review, 201);
    }
}
