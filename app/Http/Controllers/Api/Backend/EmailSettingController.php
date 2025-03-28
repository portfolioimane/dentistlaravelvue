<?php
namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;



class EmailSettingController extends Controller
{

      protected $emailService;

    // Inject the EmailService into the controller
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
public function update(Request $request)
{
    try {
        // Log the incoming request data for debugging
        Log::debug('Incoming request data:', $request->all() ?: []);

        // Validate input data
        $validated = $request->validate([
            'provider' => 'required|string',
            'host' => 'nullable|string',
            'port' => 'nullable|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        // Log the validated data
        Log::debug('Validated input data:', $validated ?: []);

        // Find existing setting or create a new one
        $setting = EmailSetting::first();
        Log::debug('Existing setting found:', $setting ? $setting->toArray() : []);

        if (!$setting) {
            // Log the creation process
            Log::debug('No existing setting found. Creating a new one.');
            $setting = EmailSetting::create([
                'provider' => $validated['provider'],
                'host' => $validated['host'] ?? null,
                'port' => $validated['port'] ?? null,
                'username' => $validated['username'] ?? null,
                'password' => $validated['password'] ?? null,
                'encryption' => $validated['encryption'] ?? null,
                'from_address' => $validated['from_address'],
                'from_name' => $validated['from_name'],
                'enabled' => $validated['enabled'],
            ]);
            Log::debug('New setting created:', $setting ? $setting->toArray() : []);
        } else {
            // Log the update process
            Log::debug('Updating existing setting:', $setting ? $setting->toArray() : []);
            $setting->update([
                'provider' => $validated['provider']  ?? $setting->provider,
                'host' => $validated['host'] ?? $setting->host,
                'port' => $validated['port'] ?? $setting->port,
                'username' => $validated['username'] ?? $setting->username,
                'password' => $validated['password'] ?? $setting->password,
                'encryption' => $validated['encryption'] ?? $setting->encryption,
                'from_address' => $validated['from_address'],
                'from_name' => $validated['from_name'],
                'enabled' => $validated['enabled'],
            ]);
            Log::debug('Existing setting updated:', $setting ? $setting->toArray() : []);
        }

        // Log the final response data
        Log::debug('Email setting update response:', $setting ? $setting->toArray() : []);



        return response()->json(['message' => 'Email setting updated successfully', 'data' => $setting]);

    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error('Error updating email settings:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => 'An error occurred while updating email settings.'], 500);
    }
}



    // Method to fetch email settings
    public function getSettings()
    {
        try {
            // Log the request to fetch email settings
            Log::debug('Fetching email settings.');


            // Retrieve all email settings (you can modify this query if needed)
            $settings = EmailSetting::all();

            // Log the fetched settings
            Log::debug('Fetched email settings:', $settings->toArray());

            return response()->json($settings);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching email settings:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An error occurred while fetching email settings.'], 500);
        }
    }
}
