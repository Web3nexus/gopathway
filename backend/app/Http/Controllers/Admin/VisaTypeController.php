<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VisaTypeRequest;
use App\Models\Country;
use App\Models\VisaType;
use Illuminate\Http\JsonResponse;

class VisaTypeController extends Controller
{
    public function index(Country $country): JsonResponse
    {
        return response()->json(['data' => $country->visaTypes]);
    }

    public function store(VisaTypeRequest $request, Country $country): JsonResponse
    {
        $visaType = $country->visaTypes()->create($request->validated());
        return response()->json(['data' => $visaType], 201);
    }

    public function update(VisaTypeRequest $request, VisaType $visaType): JsonResponse
    {
        $visaType->update($request->validated());
        return response()->json(['data' => $visaType]);
    }

    public function destroy(VisaType $visaType): JsonResponse
    {
        $visaType->delete();
        return response()->json(null, 204);
    }
}
