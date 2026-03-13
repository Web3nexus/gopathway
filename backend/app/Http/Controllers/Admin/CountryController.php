<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Http\Requests\Admin\StoreCountryRequest;
use App\Http\Requests\Admin\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Country::with(['visaTypes', 'score'])->orderBy('competitiveness_score', 'desc')->get()
        ]);
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        $country = Country::create($request->validated());
        return response()->json(['data' => $country], 201);
    }

    public function show(Country $country): JsonResponse
    {
        return response()->json($country);
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        $country->update($request->validated());
        return response()->json(['data' => $country]);
    }

    public function destroy(Country $country): JsonResponse
    {
        $country->delete();
        return response()->json(null, 204);
    }
}
