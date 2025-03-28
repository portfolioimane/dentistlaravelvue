<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\SmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsSettingController extends Controller
{
    // Method to update SMS settings
    public function update(Request $request)
    {
        try {
            // Log the incoming request data for debugging
            Log::debug('Incoming request data:', $request->all() ?: []);

            // Validate input data
            $validated = $request->validate([
                'provider' => 'required|string',
                'sid' => 'nullable|string',
                'auth_token' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'enabled' => 'required|boolean',
            ]);

            // Log the validated data
            Log::debug('Validated input data:', $validated ?: []);

            // Find existing setting or create a new one
            $setting = SmsSetting::first();
            Log::debug('Existing setting found:', $setting ? $setting->toArray() : []);

            if (!$setting) {
                // Log the creation process
                Log::debug('No existing setting found. Creating a new one.');
                $setting = SmsSetting::create($validated);
                Log::debug('New setting created:', $setting ? $setting->toArray() : []);
            } else {
                // Log the update process
                Log::debug('Updating existing setting:', $setting ? $setting->toArray() : []);
                $setting->update($validated);
                Log::debug('Existing setting updated:', $setting ? $setting->toArray() : []);
            }

            // Log the final response data
            Log::debug('SMS setting update response:', $setting ? $setting->toArray() : []);

            return response()->json(['message' => 'SMS setting updated successfully', 'data' => $setting]);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error updating SMS settings:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An error occurred while updating SMS settings.'], 500);
        }
    }

    // Method to fetch SMS settings
    public function getSettings()
    {
        try {
            // Log the request to fetch SMS settings
            Log::debug('Fetching SMS settings.');

            // Retrieve all SMS settings
            $settings = SmsSetting::all();

            // Log the fetched settings
            Log::debug('Fetched SMS settings:', $settings->toArray());

            return response()->json($settings);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching SMS settings:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An error occurred while fetching SMS settings.'], 500);
        }
    }
}
