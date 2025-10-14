<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EventParticipantController extends Controller
{
    /**
     * Join an event
     */
    public function joinEvent(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if event is published and active
        if ($event->status !== 'published' || !$event->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Event is not available for registration'
            ], 400);
        }

        // Check if event is full
        if ($event->is_full) {
            return response()->json([
                'success' => false,
                'message' => 'Event is full'
            ], 400);
        }

        // Check if user already registered
        $existingParticipant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingParticipant) {
            return response()->json([
                'success' => false,
                'message' => 'You are already registered for this event'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create participant record
            $participant = EventParticipant::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'registered',
                'is_paid' => !$event->is_paid, // Free events are automatically paid
                'amount_paid' => $event->is_paid ? 0 : null,
            ]);

            // Update event registered count
            $event->increment('registered_count');

            // Create notification for organizer
            Notification::create([
                'user_id' => $event->user_id,
                'event_id' => $event->id,
                'type' => 'event_registration',
                'title' => 'New Event Registration',
                'message' => $user->full_name . ' has registered for your event: ' . $event->title,
                'data' => [
                    'participant_id' => $participant->id,
                    'participant_name' => $user->full_name
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $event->is_paid ? 'Registration successful. Please complete payment.' : 'Successfully joined the event',
                'data' => $participant
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to join event'
            ], 500);
        }
    }

    /**
     * Cancel event participation
     */
    public function cancelParticipation(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        $participant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'You are not registered for this event'
            ], 400);
        }

        if ($participant->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Participation already cancelled'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $participant->update(['status' => 'cancelled']);
            $event->decrement('registered_count');

            // Create notification for organizer
            Notification::create([
                'user_id' => $event->user_id,
                'event_id' => $event->id,
                'type' => 'event_cancellation',
                'title' => 'Event Registration Cancelled',
                'message' => $user->full_name . ' has cancelled their registration for: ' . $event->title,
                'data' => [
                    'participant_id' => $participant->id,
                    'participant_name' => $user->full_name
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Participation cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel participation'
            ], 500);
        }
    }

    /**
     * Mark attendance using QR code
     */
    public function markAttendance(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Find event by QR code
        $event = Event::where('qr_code', 'like', '%' . $request->qr_code . '%')->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code'
            ], 400);
        }

        // Check if user is registered for this event
        $participant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'You are not registered for this event'
            ], 400);
        }

        if ($participant->status === 'attended') {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already marked'
            ], 400);
        }

        $participant->update([
            'status' => 'attended',
            'attended_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => $participant
        ]);
    }

    /**
     * Get user's event participations
     */
    public function myParticipations(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = EventParticipant::with(['event.organizer', 'event.category'])
            ->where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $participations = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $participations
        ]);
    }

    /**
     * Get event participants (Organizer only)
     */
    public function getEventParticipants(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view participants'
            ], 403);
        }

        $participants = EventParticipant::with(['user'])
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
    }

    /**
     * Process payment for paid events
     */
    public function processPayment(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        $participant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'You are not registered for this event'
            ], 400);
        }

        if ($participant->is_paid) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processed'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_reference' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify payment amount matches event price
        if ($request->amount != $event->price) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount does not match event price'
            ], 400);
        }

        $participant->update([
            'is_paid' => true,
            'amount_paid' => $request->amount,
            'payment_reference' => $request->payment_reference
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => $participant
        ]);
    }
}
