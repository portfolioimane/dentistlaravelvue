<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingsController extends Controller
{
    /**
     * Get all bookings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Log::info('Fetching all bookings'); // Log entry

        try {
            $bookings = Booking::with(['user', 'service'])->get(); // Load related user and service
            Log::info('Bookings fetched successfully', ['bookings_count' => $bookings->count()]);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch bookings'], 500);
        }

        return response()->json(['bookings' => $bookings], 200);
    }

    /**
     * Get details of a specific booking.
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($bookingId)
    {
        Log::info('Fetching booking details', ['booking_id' => $bookingId]);

        try {
            $booking = Booking::with(['user', 'service'])->find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Booking not found'], 404);
            }

            Log::info('Booking found', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            Log::error('Error fetching booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch booking'], 500);
        }

        return response()->json(['booking' => $booking], 200);
    }

    /**
     * Create a new booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Creating a new booking', ['request_data' => $request->all()]);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'booking_date' => 'required|date',
        ]);

        try {
            $booking = Booking::create($request->all());
            Log::info('Booking created successfully', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to create booking'], 500);
        }

        return response()->json(['booking' => $booking], 201);
    }

    /**
     * Update the booking details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $bookingId)
    {
        Log::info('Updating booking', ['booking_id' => $bookingId, 'request_data' => $request->all()]);

        $request->validate([
            'status' => 'sometimes|in:pending,completed,cancelled',
            'booking_date' => 'sometimes|date',
        ]);

        try {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found for update', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Booking not found'], 404);
            }

            $booking->update($request->all());
            Log::info('Booking updated successfully', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Error updating booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to update booking'], 500);
        }

        return response()->json(['booking' => $booking], 200);
    }

    /**
     * Delete a booking.
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($bookingId)
    {
        Log::info('Deleting booking', ['booking_id' => $bookingId]);

        try {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::warning('Booking not found for deletion', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Booking not found'], 404);
            }

            $booking->delete();
            Log::info('Booking deleted successfully', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            Log::error('Error deleting booking: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to delete booking'], 500);
        }

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
