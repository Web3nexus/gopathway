<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of email templates.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => EmailTemplate::all()
        ]);
    }

    /**
     * Display the specified email template.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        return response()->json([
            'status' => 'success',
            'data' => $emailTemplate
        ]);
    }

    /**
     * Update the specified email template.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $emailTemplate->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Email template updated successfully.',
            'data' => $emailTemplate
        ]);
    }
}
