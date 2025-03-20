<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Service;
use App\Models\PaymentSetting;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\BuisnessHour;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;

class BookingController extends Controller
{
    private $paypal;

    public function __construct()
    {
        // Initialize PayPal client with API credentials
        $this->paypal = new PayPalClient;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }

    /**
     * Get all bookings for the authenticated user
     */
    public function myBookings(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $bookings = Booking::where('user_id', Auth::id())
            ->with('service')
            ->paginate($perPage);

        return response()->json([
            'bookings' => $bookings,
        ], 200);
    }

    /**
     * Get details of a specific booking
     */
    public function show($id)
    {
        $booking = Booking::with('service')->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking, 200);
    }

    /**
     * Create a new booking
     */
public function create(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'service_id' => 'required|exists:services,id',
        'date' => 'required|date', // Ensure date is passed
        'start_time' => 'required|date_format:H:i', // Ensure time format is correct
        'end_time' => 'required|date_format:H:i|after:start_time', // Ensure end_time is after start_time
        'payment_method' => 'required|string',
        'total' => 'required|numeric',
    ]);

    // Retrieve the selected service
    $service = Service::find($validated['service_id']);
    if (!$service) {
        return response()->json(['message' => 'Service not found'], 404);
    }

    // Check if the time slot is available for the selected service and date
    $existingBooking = Booking::where('date', $validated['date'])
        ->where('service_id', $service->id)
        ->where(function ($query) use ($validated) {
            $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
        })
        ->exists();

    if ($existingBooking) {
        return response()->json(['message' => 'The selected time slot is already booked'], 400);
    }

    // Set booking status and paid_amount based on payment_method
    $status = 'pending'; // Default status
    $paidAmount = 0;

    if (in_array($validated['payment_method'], ['stripe', 'paypal'])) {
        $status = 'completed'; // Set status to confirmed for Stripe/PayPal
        $paidAmount = 50; // Set the booking fee as the paid amount
    }

    // Get the authenticated user ID
    $userId = Auth::id(); // This is from Laravel Sanctum authentication

    // If service and time are available, proceed to create the booking
    $booking = Booking::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'service_id' => $validated['service_id'],
        'date' => $validated['date'],
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
        'payment_method' => $validated['payment_method'],
        'total' => $validated['total'],
        'status' => $status,
        'paid_amount' => $paidAmount, // Set paid amount to 50 if payment is via Stripe/PayPal
        'user_id' => $userId, // Add the user_id for the authenticated user
    ]);

    return response()->json([
        'message' => 'Booking initiated successfully!',
        'booking' => $booking,
    ], 201);
}



    /**
     * Create Stripe payment for booking
     */
    public function createStripePayment(Request $request)
    {
        try {
            $totalAmount = $request->input('total');
            $amountInCents = $totalAmount * 100;

            $stripeSettings = PaymentSetting::where('provider', 'stripe')->where('enabled', true)->first();
            if (!$stripeSettings || !$stripeSettings->secret_key) {
                return response()->json(['error' => 'Stripe is not configured'], 500);
            }

            Stripe::setApiKey($stripeSettings->secret_key);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            Log::info('Stripe payment intent created', ['payment_intent_id' => $paymentIntent->id]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret], 200);
        } catch (\Exception $e) {
            Log::error('Error creating Stripe payment intent', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create payment intent'], 500);
        }
    }

    /**
     * Confirm PayPal payment for booking
     */
    public function confirmPaypalPayment(Request $request)
    {
        Log::info('Confirm PayPal Payment API called', ['request' => $request->all()]);

        $paypalOrderId = $request->paypalOrderId;

        if (!$paypalOrderId) {
            Log::error('PayPal Order ID is missing in the request.');
            return response()->json(['error' => 'PayPal Order ID is required'], 400);
        }

        try {
            $orderDetails = $this->paypal->showOrderDetails($paypalOrderId);
            Log::info('Order details fetched from PayPal.', ['orderDetails' => $orderDetails]);

            if (isset($orderDetails['status']) && $orderDetails['status'] === 'COMPLETED') {
                Log::info('PayPal order completed successfully.', ['paypalOrderId' => $paypalOrderId]);
                return response()->json(['status' => 'COMPLETED']);
            }

            Log::warning('PayPal order not completed.', ['orderDetails' => $orderDetails]);
            return response()->json(['status' => 'NOT_COMPLETED'], 400);
        } catch (\Exception $e) {
            Log::error('Error while confirming PayPal payment.', [
                'paypalOrderId' => $paypalOrderId,
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to confirm PayPal payment'], 500);
        }
    }


public function getAvailableSlots(Request $request)
{
    // Fetch available slots based on business hours and existing bookings
    $date = $request->query('date');
    $serviceId = $request->query('service_id');

    // Log the input parameters
    Log::info('Fetching available slots for date: ' . $date . ' and service_id: ' . $serviceId);

    // Get business hours for the selected day
    $businessHours = BuisnessHour::where('day', date('l', strtotime($date)))->first();

    if (!$businessHours) {
        Log::error('Business hours not set for this day: ' . date('l', strtotime($date)));
        return response()->json(['message' => 'Business hours not set for this day'], 404);
    }

    $startTime = Carbon::parse($businessHours->open_time)->format('H:i');
    $endTime = Carbon::parse($businessHours->close_time)->format('H:i');

    // Log business hours
    Log::info('Business hours for ' . date('l', strtotime($date)) . ': ' . $startTime . ' to ' . $endTime);

    // Get the selected service to determine its duration
    $service = Service::find($serviceId);

    if (!$service) {
        Log::error('Service not found for service_id: ' . $serviceId);
        return response()->json(['message' => 'Service not found'], 404);
    }

    // Log the service duration
    Log::info('Service found for service_id: ' . $serviceId . ', duration: ' . $service->duration . ' minutes');

    // Get existing *completed* bookings for the selected date and service
    $bookings = Booking::where('date', $date)
        ->where('service_id', $serviceId)
        ->where('status', 'completed') // Only consider completed bookings
        ->get();

    // Log the number of completed bookings
    Log::info('Found ' . $bookings->count() . ' completed bookings for date: ' . $date . ' and service_id: ' . $serviceId);

    // Calculate available slots
    $availableSlots = [];
    $currentTime = Carbon::createFromFormat('H:i', $startTime);

    while ($currentTime->format('H:i') < $endTime) {
        $slotStart = $currentTime->format('H:i');
        $slotEnd = $currentTime->copy()->addMinutes($service->duration)->format('H:i');

        // Check if this slot is already booked (Only consider completed bookings)
        $isBooked = $bookings->contains(function ($booking) use ($slotStart, $slotEnd) {
            return ($booking->start_time < $slotEnd && $booking->end_time > $slotStart);
        });

        if (!$isBooked) {
            $availableSlots[] = [
                'start_time' => $slotStart,
                'end_time' => $slotEnd,
            ];
        }

        // Move to the next slot using service duration
        $currentTime->addMinutes($service->duration);
    }

    // Log available slots
    Log::info('Available slots calculated: ' . count($availableSlots));

    return response()->json($availableSlots); // Return available slots as JSON response
}





}
