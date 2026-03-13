<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;

class RelocationKitsController extends Controller
{
    /**
     * Get all relocation kits for a given country.
     */
    public function index(Request $request, Country $country)
    {
        $kits = $country->relocationKits()
            ->with(['items' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return response()->json([
            'data' => $kits,
        ]);
    }
}
