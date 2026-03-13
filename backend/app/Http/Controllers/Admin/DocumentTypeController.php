<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Http\JsonResponse;

class DocumentTypeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => DocumentType::with('visaType.country')->get()
        ]);
    }

    public function store(DocumentTypeRequest $request): JsonResponse
    {
        $documentType = DocumentType::create($request->validated());
        return response()->json(['data' => $documentType], 201);
    }

    public function update(DocumentTypeRequest $request, DocumentType $documentType): JsonResponse
    {
        $documentType->update($request->validated());
        return response()->json(['data' => $documentType]);
    }

    public function destroy(DocumentType $documentType): JsonResponse
    {
        $documentType->delete();
        return response()->json(null, 204);
    }
}
