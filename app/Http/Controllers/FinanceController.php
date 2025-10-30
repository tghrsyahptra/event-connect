<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventParticipant;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $organizerId = Auth::id();

        $eventsQuery = Event::withCount(['participants as paid_participants_count' => function ($q) {
                $q->where('is_paid', true);
            }])
            ->withSum(['participants as total_revenue' => function ($q) {
                $q->where('is_paid', true);
            }], 'amount_paid')
            ->where('user_id', $organizerId);

        if ($request->filled('search')) {
            $s = $request->search;
            $eventsQuery->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $eventsQuery->where('status', $request->status);
        }

        $events = $eventsQuery->orderBy('start_date', 'desc')->paginate(10);

        $summary = [
            'total_events' => (clone $eventsQuery)->count(),
            'total_paid_registrations' => EventParticipant::whereHas('event', function ($q) use ($organizerId) {
                $q->where('user_id', $organizerId);
            })->where('is_paid', true)->count(),
            'total_revenue' => EventParticipant::whereHas('event', function ($q) use ($organizerId) {
                $q->where('user_id', $organizerId);
            })->where('is_paid', true)->sum('amount_paid'),
            'pending_payments' => EventParticipant::whereHas('event', function ($q) use ($organizerId) {
                $q->where('user_id', $organizerId);
            })->where('payment_status', 'pending')->count(),
        ];

        return view('admin.finance.index', compact('events', 'summary'));
    }

    public function show(Event $event)
    {
        // Ensure organizer owns the event (super admin handled by middleware stack)
        if ($event->user_id !== Auth::id() && !(method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())) {
            abort(403, 'Unauthorized');
        }

        $participants = EventParticipant::where('event_id', $event->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        $metrics = [
            'price' => $event->price,
            'total_registered' => $event->registered_count,
            'paid' => EventParticipant::where('event_id', $event->id)->where('is_paid', true)->count(),
            'revenue' => EventParticipant::where('event_id', $event->id)->where('is_paid', true)->sum('amount_paid'),
            'pending' => EventParticipant::where('event_id', $event->id)->where('payment_status', 'pending')->count(),
            'failed' => EventParticipant::where('event_id', $event->id)->where('payment_status', 'failed')->count(),
            'cancelled' => EventParticipant::where('event_id', $event->id)->where('status', 'cancelled')->count(),
        ];

        return view('admin.finance.show', compact('event', 'participants', 'metrics'));
    }
}


