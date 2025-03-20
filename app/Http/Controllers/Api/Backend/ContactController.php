<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
public function index()
    {
        // Fetch all contact messages (You can add pagination if needed)
        $messages = Contact::all();

        // Return the messages as a JSON response
        return response()->json($messages);
    }
}
