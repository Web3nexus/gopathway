<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * List all platform features and their premium requirements.
     */
    public function index()
    {
        return response()->json([
            'data' => Feature::all()
        ]);
    }
}
