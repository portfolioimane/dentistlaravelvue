<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;

class KeysController extends Controller
{
    /**
     * Get the Stripe public key and its enabled status for frontend usage.
     */
    public function getStripePublicKey()
    {
        $stripeSettings = PaymentSetting::where('provider', 'stripe')->first();

        if (!$stripeSettings || !$stripeSettings->public_key) {
            return response()->json(['error' => 'Stripe public key not found'], 500);
        }

        return response()->json([
            'publicKey' => $stripeSettings->public_key,
            'isStripeEnabled' => $stripeSettings->enabled
        ], 200);
    }

    /**
     * Get the PayPal public key and its enabled status for frontend usage.
     */
    public function getPaypalPublicKey()
    {
        $paypalSettings = PaymentSetting::where('provider', 'paypal')->first();

        if (!$paypalSettings || !$paypalSettings->public_key) {
            return response()->json(['error' => 'PayPal public key not found'], 500);
        }

        return response()->json([
            'publicKey' => $paypalSettings->public_key,
            'isPaypalEnabled' => $paypalSettings->enabled
        ], 200);
    }
}
