<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // If professional, show bookings where they are the professional
        if ($user->hasRole(['lawyer', 'translator'])) {
            return Booking::where('professional_id', $user->id)
                ->with('user')
                ->latest()
                ->get();
        }

        // Otherwise show bookings where they are the user
        return Booking::where('user_id', $user->id)
            ->with('professional')
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'professional_id' => 'required|exists:users,id',
            'type' => 'required|in:consultation,translation',
            'scheduled_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'professional_id' => $request->professional_id,
            'type' => $request->type,
            'status' => 'pending',
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
        ]);

        // Notify the professional about the new booking request
        Notification::create([
            'user_id' => $request->professional_id,
            'title' => 'New booking request',
            'message' => $request->user()->name . ' has requested a ' . $request->type . ' session.',
        ]);

        return response()->json([
            'message' => 'Booking request submitted successfully.',
            'booking' => $booking->load('professional'),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $user = $request->user();

        // Only the professional can update the status
        if ($booking->professional_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        // Notify the user about the booking status update
        $statusText = match ($request->status) {
                'confirmed' => 'Your booking has been confirmed!',
                'completed' => 'Your booking has been marked as completed.',
                'cancelled' => 'Your booking has been cancelled.',
                default => 'Your booking status has been updated.',
            };
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => $statusText,
            'message' => 'Booking with ' . $user->name . ' is now ' . $request->status . '.',
        ]);

        return response()->json([
            'message' => 'Booking status updated successfully.',
            'booking' => $booking,
        ]);
    }

    public function marketplace()
    {
        // Return verified professionals
        return User::role(['lawyer', 'translator'])
            ->whereHas('professionalProfile', function ($query) {
            $query->where('is_verified', true);
        })
            ->with('professionalProfile')
            ->get();
    }

    public function adminIndex()
    {
        return Booking::with(['user', 'professional'])
            ->latest()
            ->get();
    }
}