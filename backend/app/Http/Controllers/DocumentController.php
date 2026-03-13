<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Get required document types for the user's current pathway.
     */
    public function requiredTypes(Request $request)
    {
        $user = $request->user();
        $pathway = $user->pathway()->latest()->first();

        if (!$pathway) {
            // Return some default common types if no pathway is selected yet
            return response()->json([
                'data' => [
                    ['id' => 'passport', 'name' => 'International Passport', 'description' => 'Valid for at least 6 months'],
                    ['id' => 'photo', 'name' => 'Passport Photograph', 'description' => 'Recent digital photo (white background)'],
                ]
            ]);
        }

        $typesQuery = DocumentType::where('visa_type_id', $pathway->visa_type_id)
            ->orWhereNull('visa_type_id'); // Global requirements

        $types = $typesQuery->get();

        // If user has school applications, ensure academic documents are in the checklist
        $hasSchoolApp = \App\Models\UserSchoolApplication::where('user_id', $user->id)->exists();
        if ($hasSchoolApp) {
            $academicDocNames = [
                'Academic Transcripts',
                'English Test Result',
                'CV / Resume',
                'Motivation Letter',
            ];
            $academicTypes = DocumentType::whereIn('name', $academicDocNames)->get();
            // Merge without duplicates
            $types = $types->merge($academicTypes)->unique('id')->values();
        }

        return response()->json(['data' => $types]);
    }

    /**
     * List all user documents.
     */
    public function index(Request $request)
    {
        $documents = $request->user()
            ->documents()
            ->with('documentType')
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'status' => $d->status,
                'file_path' => $d->file_path,
                'file_size' => $d->file_size,
                'document_type' => $d->documentType?->only(['id', 'name']),
                'uploaded_at' => $d->created_at,
            ]);

        return response()->json(['data' => $documents]);
    }

    /**
     * Upload a document.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        // Sanitize filename to prevent directory traversal or other attacks
        $safeName = preg_replace('/[^A-Za-z0-9\._-]/', '', $originalName);
        
        $path = $file->storeAs("documents/{$request->user()->id}", $safeName, 'local');

        \App\Helpers\Security::log('file_uploaded', 'low', "User uploaded a document: {$safeName}", ['original_name' => $originalName, 'document_type_id' => $request->document_type_id]);

        $doc = $request->user()->documents()->create([
            'document_type_id' => $request->document_type_id,
            'name' => $request->name ?? $originalName,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'status' => 'uploaded',
        ]);

        return response()->json([
            'data' => [
                'id' => $doc->id,
                'name' => $doc->name,
                'status' => $doc->status,
                'file_size' => $doc->file_size,
            ]
        ], 201);
    }
}