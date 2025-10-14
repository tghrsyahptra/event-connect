<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FeedbackController extends Controller
{
    /**
     * Submit feedback for an event
     */
    public function store(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user attended the event
        $participant = EventParticipant::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->where('status', 'attended')
            ->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'You must attend the event to submit feedback'
            ], 400);
        }

        // Check if event has ended
        if ($event->end_date > now()) {
            return response()->json([
                'success' => false,
                'message' => 'Event has not ended yet'
            ], 400);
        }

        // Check if feedback already submitted
        $existingFeedback = Feedback::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingFeedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback already submitted for this event'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback = Feedback::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Generate certificate
        $this->generateCertificate($feedback);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully. Certificate generated.',
            'data' => $feedback
        ], 201);
    }

    /**
     * Get user's feedbacks
     */
    public function myFeedbacks(Request $request): JsonResponse
    {
        $user = $request->user();

        $feedbacks = Feedback::with(['event.organizer', 'event.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $feedbacks
        ]);
    }

    /**
     * Get feedback for an event (Organizer only)
     */
    public function getEventFeedbacks(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view feedbacks'
            ], 403);
        }

        $feedbacks = Feedback::with(['user'])
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $feedbacks
        ]);
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        $feedback = Feedback::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ], 404);
        }

        if (!$feedback->certificate_generated) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not generated yet'
            ], 400);
        }

        $certificatePath = storage_path('app/public/' . $feedback->certificate_path);

        if (!file_exists($certificatePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate file not found'
            ], 404);
        }

        return response()->download($certificatePath, 'certificate_' . $event->title . '.pdf');
    }

    /**
     * Get certificate URL
     */
    public function getCertificateUrl(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        $feedback = Feedback::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ], 404);
        }

        if (!$feedback->certificate_generated) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not generated yet'
            ], 400);
        }

        $certificateUrl = asset('storage/' . $feedback->certificate_path);

        return response()->json([
            'success' => true,
            'data' => [
                'certificate_url' => $certificateUrl
            ]
        ]);
    }

    /**
     * Generate certificate for feedback
     */
    private function generateCertificate(Feedback $feedback): void
    {
        $event = $feedback->event;
        $user = $feedback->user;

        // Generate certificate filename
        $filename = 'certificate_' . $event->id . '_' . $user->id . '_' . time() . '.pdf';
        $certificatePath = 'certificates/' . $filename;

        // Create PDF certificate
        $pdf = Pdf::loadView('certificates.event', [
            'event' => $event,
            'user' => $user,
            'feedback' => $feedback,
            'date' => now()->format('F j, Y')
        ]);

        // Save certificate
        Storage::disk('public')->put($certificatePath, $pdf->output());

        // Update feedback with certificate path
        $feedback->update([
            'certificate_generated' => true,
            'certificate_path' => $certificatePath
        ]);
    }
}
