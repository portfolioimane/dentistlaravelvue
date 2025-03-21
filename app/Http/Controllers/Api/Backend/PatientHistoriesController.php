<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatientHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PatientHistoriesController extends Controller
{
    // Fetch all patient histories for a specific patient


public function index($patientId)
{
    // Log the patient ID received
    Log::info('Fetching patient histories for patient ID: ' . $patientId);

    // Find the patient or fail
    $patient = User::findOrFail($patientId);

    // Log the patient information
    Log::info('Patient found: ', ['patient' => $patient]);

    // Fetch the patient's histories
    $histories = $patient->histories;

    // Log the histories
    Log::info('Patient Histories: ', ['histories' => $histories]);

    // Return the patient histories as JSON response
    return response()->json($histories);
}


    // Fetch a specific patient history by ID
    public function getHistory($historyId)
    {
        $history = PatientHistory::findOrFail($historyId);
        Log::info('Fetched patient history by ID', ['history' => $history]);
        return response()->json($history);
    }

public function store(Request $request, $patientId)
{
    // Log the incoming request data
    Log::info('Received request to store patient history for patient ID: ' . $patientId, [
        'request_data' => $request->all(),
    ]);

    // Validate input
    $validated = $request->validate([
        'treatment_date' => 'required|date',
        'treatment_details' => 'required|string',
        'dentist_name' => 'required|string|max:255',
        'treatment_type' => 'required',
        'treatment_cost' => 'required|numeric|between:0,999999.99', // Ensures it allows up to two decimal places
        'amount_paid' => 'required|numeric|between:0,999999.99', // Ensures it allows up to two decimal places
        'prescriptions' => 'nullable|string',
        'follow_up_instructions' => 'nullable|string',
        'is_completed' => 'nullable|boolean',
    ]);

    // Log the validated data
    Log::info('Validated data for new patient history: ', [
        'validated_data' => $validated,
    ]);

    // Calculate remaining balance
    $remainingBalance = $validated['treatment_cost'] - $validated['amount_paid'];

    // Log the remaining balance calculation
    Log::info('Calculated remaining balance: ', [
        'remaining_balance' => $remainingBalance,
    ]);

    // Find the patient or fail
    $patient = User::findOrFail($patientId);

    // Log patient information
    Log::info('Patient found for ID ' . $patientId, [
        'patient_info' => $patient,
    ]);

    // Create patient history
    $history = new PatientHistory($validated);
    $history->user_id = $patient->id;
    $history->remaining_balance = $remainingBalance;  // Store the calculated balance
    $history->save();

    // Log the created history
    Log::info('Created new patient history: ', [
        'history' => $history,
    ]);

    // Return the created history as a JSON response
    return response()->json($history, 201);
}


public function update(Request $request, $historyId)
{
    // Validate input (optional fields)
    $validator = Validator::make($request->all(), [
        'treatment_date' => 'sometimes|required|date',
        'treatment_details' => 'sometimes|required|string',
        'dentist_name' => 'sometimes|required|string|max:255',
        'treatment_type' => 'sometimes', // You can add a validation rule for treatment_type if necessary
        'treatment_cost' => 'sometimes|numeric|between:0,999999.99',
        'amount_paid' => 'sometimes|numeric|between:0,999999.99',
        'prescriptions' => 'nullable|string',
        'follow_up_instructions' => 'nullable|string',
        'is_completed' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed on update', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Retrieve validated data from the request
    $validated = $validator->validated();

    // Calculate the remaining balance if both treatment cost and amount paid are provided
    $remainingBalance = 0; // Default value
    if (isset($validated['treatment_cost']) && isset($validated['amount_paid'])) {
        $remainingBalance = $validated['treatment_cost'] - $validated['amount_paid'];
    }

    // Find the patient history entry
    $history = PatientHistory::findOrFail($historyId);

    // Prepare the data to update
    $data = $validated;

    // Update the remaining balance in the data array (add or update the remaining_balance field)
    $data['remaining_balance'] = $remainingBalance;

    // Update the patient history entry with the new data
    $history->update($data);

    // Return the updated history entry as a JSON response
    return response()->json($history);
}


    // Delete a patient history entry
    public function destroy($historyId)
    {
        $history = PatientHistory::findOrFail($historyId);
        $history->delete();

        return response()->json(null, 204);
    }
}
