<?php
// app/Http/Controllers/HomepageHeaderController.php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomepageHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomePageHeaderController extends Controller
{
    public function getHeader()
    {
        // Fetch the first homepage header record
        $header = HomepageHeader::first();

        if ($header) {
            return response()->json($header);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Header not found.',
            ], 404);
        }
    }
public function update(Request $request)
{
    $request->validate([
        'title' => 'sometimes|string|max:255',
        'subtitle' => 'sometimes|string|max:255',
        'buttonText' => 'sometimes|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    Log::debug('Request Data:', $request->all());

    try {
        $header = HomepageHeader::first();
        if (!$header) {
            return response()->json(['message' => 'Header not found.'], 404);
        }

        // Update fields if present
        if ($request->has('title')) {
            $header->title = $request->title;
        }

        if ($request->has('subtitle')) {
            $header->subtitle = $request->subtitle;
        }

        if ($request->has('buttonText')) {
            $header->buttonText = $request->buttonText;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($header->image && Storage::disk('public')->exists($header->image)) {
                Storage::disk('public')->delete($header->image);
            }

            // Store the new image in the public directory
            $imagePath = $request->file('image')->store('images/homepage_headers', 'public');

            // Store the path as '/storage/images/homepage_headers/...' for access
            $header->image = '/storage/' . $imagePath;  // Store with '/storage/' prefix for URL
        }

        $header->save();

        return response()->json($header, 200);
    } catch (\Exception $e) {
        Log::error('Error updating homepage header:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'An error occurred while updating the header.'], 500);
    }
}



}
