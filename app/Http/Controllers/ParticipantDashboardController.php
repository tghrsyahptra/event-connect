<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class ParticipantDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get all user's event participations with event details
        $myEvents = EventParticipant::where('user_id', $userId)
            ->with(['event.organizer', 'event.category', 'event.feedbacks'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_registered' => $myEvents->count(),
            'attended_events' => $myEvents->where('attendance_status', 'attended')->count(),
            'upcoming_events' => $myEvents->filter(function ($participation) {
                return $participation->event->start_date->isFuture() && 
                       $participation->registration_status === 'approved';
            })->count(),
            'certificates' => Feedback::where('user_id', $userId)
                ->where('certificate_generated', true)
                ->count(),
        ];

        // Get recent activities
        $recentActivities = $this->getRecentActivities($userId);

        return view('participant.dashboard', compact('myEvents', 'stats', 'recentActivities'));
    }

    private function getRecentActivities($userId)
    {
        $activities = [];

        // Get recent participations
        $recentParticipations = EventParticipant::where('user_id', $userId)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentParticipations as $participation) {
            $activities[] = [
                'message' => 'Joined "' . $participation->event->title . '"',
                'time' => $participation->created_at->diffForHumans(),
                'color' => 'green',
            ];
        }

        // Get recent feedbacks
        $recentFeedbacks = Feedback::where('user_id', $userId)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        foreach ($recentFeedbacks as $feedback) {
            $activities[] = [
                'message' => 'Submitted feedback for "' . $feedback->event->title . '"',
                'time' => $feedback->created_at->diffForHumans(),
                'color' => 'blue',
            ];
        }

        // Sort by time and limit to 5
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 5);
    }
}