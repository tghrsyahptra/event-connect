<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Show QR scanner page for participant
     */
    public function scanner()
    {
        return view('participant.attendance.scanner');
    }

    /**
     * Show QR code display for Event Organizer
     */
    public function showQRCode(Event $event)
    {
        // Check if user is organizer of this event or super admin
        if ($event->user_id !== Auth::id() && !Auth::user()->isAdmin() && !(method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())) {
            abort(403, 'You are not authorized to view this QR code');
        }

        return view('admin.events.qr-code', compact('event'));
    }

    /**
     * Mark attendance via web interface (for participant)
     */
    public function markAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Find participant by their unique QR code
        // QR code contains the unique string (e.g., "user_1_event_1_1234567890_abc123")
        $qrCodeString = $request->qr_code;
        $participant = EventParticipant::where('qr_code_string', $qrCodeString)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return redirect()->back()
                ->with('error', 'Invalid QR code or you are not authorized to use this QR code');
        }

        $event = $participant->event;

        if ($participant->status === 'attended') {
            return redirect()->back()
                ->with('error', 'Attendance already marked');
        }

        $participant->update([
            'status' => 'attended',
            'attended_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Attendance marked successfully! Event: ' . $event->title);
    }

    /**
     * Get event participants list for organizer
     */
    public function getParticipants(Event $event)
    {
        // Check if user is organizer or super admin
        if ($event->user_id !== Auth::id() && !Auth::user()->isAdmin() && !(method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())) {
            abort(403, 'You are not authorized to view participants');
        }

        $participants = EventParticipant::with('user')
            ->where('event_id', $event->id)
            ->orderBy('attended_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.events.participants', compact('event', 'participants'));
    }
}

