<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::where('is_active', true)
            ->with(['score', 'visaTypes'])
            ->withCount('visaTypes')
            ->orderBy('competitiveness_score', 'desc')
            ->get();

        return CountryResource::collection($countries);
    }

    public function show(Country $country)
    {
        $country->load('visaTypes');
        return new CountryResource($country);
    }
}
