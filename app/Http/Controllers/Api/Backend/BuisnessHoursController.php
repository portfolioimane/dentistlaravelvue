<?php

namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Models\BuisnessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuisnessHoursController extends Controller
{
    // Get buisness hours

public function getBuisnessHours()
{
    // Assuming you have a table called 'buisness_hours' and the data is stored there
    $buisnessHours = BuisnessHour::all(); // Or use appropriate query to get specific data if needed

    // Log the retrieved business hours data to check its content
    Log::info('Business Hours Data:', $buisnessHours->toArray());

    return response()->json($buisnessHours);
}


    // Update buisness hours
    public function updateBuisnessHours(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'monday' => 'required|array',
            'tuesday' => 'required|array',
            'wednesday' => 'required|array',
            'thursday' => 'required|array',
            'friday' => 'required|array',
            'saturday' => 'required|array',
            'sunday' => 'required|array',
        ]);

        // Update the buisness hours for each day
        foreach ($validated as $day => $hours) {
            $buisnessHour = BuisnessHour::where('day', $day)->first();
            if ($buisnessHour) {
                $buisnessHour->open_time = $hours['open_time'];
                $buisnessHour->close_time = $hours['close_time'];
                $buisnessHour->save();
            } else {
                BuisnessHour::create([
                    'day' => $day,
                    'open_time' => $hours['open_time'],
                    'close_time' => $hours['close_time'],
                ]);
            }
        }

        return response()->json(['message' => 'Buisness hours updated successfully']);
    }
}
