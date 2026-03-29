<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisaTypeResource;
use App\Models\Country;

class VisaTypeController extends Controller
{
    public function byCountry(Country $country)
    {
        $visaTypes = $country->visaTypes()
            ->with(['costTemplates'])
            ->where('is_active', true)
            ->get();
        return VisaTypeResource::collection($visaTypes);
    }
}
