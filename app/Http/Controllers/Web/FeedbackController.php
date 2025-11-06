<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    /**
     * Show feedback form for an event
     */
    public function create(Event $event)
    {
        $user = Auth::user();

        // Check if user is a participant and has attended
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'You must join this event first.');
        }

        // Check attendance status
        if ($participant->attendance_status !== 'attended') {
            return redirect()->back()->with('error', 'You must attend the event before giving feedback.');
        }

        // Check if event has ended
        if ($event->end_date > now()) {
            return redirect()->back()->with('error', 'Event has not ended yet. Please wait until the event is finished.');
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingFeedback) {
            return redirect()->route('participant.dashboard')
                ->with('info', 'You have already submitted feedback for this event.');
        }

        return view('participant.feedback.create', compact('event'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request, Event $event)
    {
        $user = Auth::user();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Check if user is a participant and has attended
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('attendance_status', 'attended')
            ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'You must attend the event before giving feedback.');
        }

        // Check if event has ended
        if ($event->end_date > now()) {
            return redirect()->back()->with('error', 'Event has not ended yet.');
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingFeedback) {
            return redirect()->back()->with('info', 'You have already submitted feedback for this event.');
        }

        // Create feedback
        $feedback = Feedback::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Generate certificate
        $this->generateCertificate($feedback);

        return redirect()->route('participant.dashboard')
            ->with('success', 'Thank you for your feedback! Your certificate has been generated and is ready to download.');
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(Event $event)
    {
        $user = Auth::user();

        // Check if user has given feedback
        $feedback = Feedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$feedback) {
            return redirect()->back()->with('error', 'You must submit feedback before downloading the certificate.');
        }

        // Check if user attended the event
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('attendance_status', 'attended')
            ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'You must attend the event to get a certificate.');
        }

        // Generate certificate if not generated yet
        if (!$feedback->certificate_generated || !$feedback->certificate_path) {
            $this->generateCertificate($feedback);
        }

        // Generate PDF on-the-fly
        $pdf = Pdf::loadView('certificates.event', [
            'user' => $user,
            'event' => $event,
            'feedback' => $feedback,
            'date' => now()->format('F j, Y'),
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        $filename = 'certificate_' . str_replace(' ', '_', $event->title) . '_' . $user->id . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * View certificate online
     */
    public function viewCertificate(Event $event)
    {
        $user = Auth::user();

        // Check if user has given feedback
        $feedback = Feedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$feedback) {
            return redirect()->back()->with('error', 'You must submit feedback before viewing the certificate.');
        }

        // Check if user attended the event
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('attendance_status', 'attended')
            ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'You must attend the event to get a certificate.');
        }

        return view('certificates.event', [
            'user' => $user,
            'event' => $event,
            'feedback' => $feedback,
            'date' => now()->format('F j, Y'),
        ]);
    }

    /**
     * Generate certificate and mark as generated
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

        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        // Save certificate to storage
        Storage::disk('public')->put($certificatePath, $pdf->output());

        // Update feedback with certificate info
        $feedback->update([
            'certificate_generated' => true,
            'certificate_path' => $certificatePath,
        ]);
    }

    /**
     * Show user's feedbacks
     */
    public function myFeedbacks()
    {
        $feedbacks = Feedback::where('user_id', Auth::id())
            ->with('event.organizer', 'event.category')
            ->latest()
            ->paginate(10);

        return view('participant.feedback.index', compact('feedbacks'));
    }
}