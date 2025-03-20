<?php
namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|string|in:stripe,paypal',
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'enabled' => 'required|boolean',
            'mode' => 'required|string|in:sandbox,live', // Add this line for mode validation
        ]);

        $setting = PaymentSetting::where('provider', $validated['provider'])->first();

        if (!$setting) {
            $setting = PaymentSetting::create([
                'provider' => $validated['provider'],
                'enabled' => $validated['enabled'],
                'public_key' => $validated['public_key'] ?? null,
                'secret_key' => $validated['secret_key'] ?? null,
                'mode' => $validated['mode'], // Store mode value
            ]);
        } else {
            $setting->update([
                'enabled' => $validated['enabled'],
                'public_key' => $validated['public_key'] ?? $setting->public_key,
                'secret_key' => $validated['secret_key'] ?? $setting->secret_key,
                'mode' => $validated['mode'], // Update mode value
            ]);
        }

        return response()->json(['message' => 'Payment setting updated successfully', 'data' => $setting]);
    }

    // Add the GET endpoint for fetching payment settings
    public function getSettings()
    {
        $settings = PaymentSetting::all(); // Get all payment settings (you can modify this query if needed)
        return response()->json($settings);
    }
}
