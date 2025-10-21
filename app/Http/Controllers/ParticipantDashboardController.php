<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class ParticipantDashboardController extends Controller
{
    public function __construct()
    {
        // Role validation is handled by middleware in routes
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get user's registered events
        $registeredEvents = EventParticipant::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get upcoming events
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->where('status', 'active')
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();
        
        // Get user statistics
        $stats = [
            'total_registered' => $registeredEvents->count(),
            'attended_events' => $registeredEvents->where('status', 'attended')->count(),
            'upcoming_events' => $registeredEvents->where('status', 'registered')->count(),
        ];

        return view('participant.dashboard', compact('registeredEvents', 'upcomingEvents', 'stats'));
    }
}
