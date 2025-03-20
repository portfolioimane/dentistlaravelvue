<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralCustomize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class GeneralCustomizeController extends Controller
{


public function uploadLogo(Request $request)
{
    // Validate the logo file
    $request->validate([
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Store the uploaded logo in the 'logos' directory inside 'storage/app/public'
    $logoPath = $request->file('logo')->store('logos', 'public'); // This stores in the 'public' disk

    // Modify the path to store as 'storage/logos' directly in the database
    $logoDatabasePath = '/storage/logos/' . basename($logoPath); // Add 'logos/' in the path

    // Update or create the logo entry in the 'general_customize' table
    GeneralCustomize::updateOrCreate(
        ['key' => 'logo'], // You can use 'logo' as the unique key
        ['value' => $logoDatabasePath] // Store the modified path in the 'value' column
    );

    // Return the URL to the uploaded logo file (using the correct public URL)
    return response()->json(['logo' => $logoDatabasePath], 200);
}



    public function updateOrCreate(Request $request)
    {
               // Validate incoming data
        $request->validate([
            'footer' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gmail' => 'nullable|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        // Store the fields in an array
        $updatedFields = $request->only([
            'footer',
            'address',
            'phone',
            'gmail',
            'facebook',
            'instagram',
            'tiktok',
        ]);

        // Update or create the GeneralCustomize values
        foreach ($updatedFields as $key => $value) {
            if ($value) {
                GeneralCustomize::updateOrCreate(
                    ['key' => $key], // Find by 'key'
                    ['value' => $value] // Update or create the value
                );
            }
        }

        // Return a response
        return response()->json(['message' => 'General customize updated successfully.'], 200);
    }
    

    /**
     * Fetch all customizations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $generalCustomizes = GeneralCustomize::all();
        return response()->json($generalCustomizes);
    }
}
