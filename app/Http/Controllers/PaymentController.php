<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show payment success page
     */
    public function success(Request $request): View
    {
        $participantId = $request->get('participant_id');
        $status = $request->get('status', 'success');

        $participant = null;
        $event = null;

        if ($participantId) {
            $participant = EventParticipant::with(['event', 'user'])->find($participantId);
            if ($participant) {
                $event = $participant->event;
            }
        }

        return view('payments.success', compact('participant', 'event', 'status'));
    }

    /**
     * Show payment failure page
     */
    public function failure(Request $request): View
    {
        $participantId = $request->get('participant_id');
        $status = $request->get('status', 'failed');

        $participant = null;
        $event = null;

        if ($participantId) {
            $participant = EventParticipant::with(['event', 'user'])->find($participantId);
            if ($participant) {
                $event = $participant->event;
            }
        }

        return view('payments.failure', compact('participant', 'event', 'status'));
    }

    /**
     * Show payment status page
     */
    public function status(Request $request, EventParticipant $participant): View
    {
        $participant->load(['event', 'user']);
        
        return view('payments.status', compact('participant'));
    }
}