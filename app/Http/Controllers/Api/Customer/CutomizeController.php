<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\GeneralCustomize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CutomizeController extends Controller
{
    public function index()
    {
        $generalCustomizes = GeneralCustomize::all();
        return response()->json($generalCustomizes);
    }
}
