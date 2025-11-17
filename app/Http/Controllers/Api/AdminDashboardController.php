<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\EventParticipant;
use App\Models\Feedback;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get admin dashboard overview data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $organizerId = auth()->id();
            $organizerEvents = Event::where('user_id', $organizerId);
            $eventIds = $organizerEvents->pluck('id');
            $participants = EventParticipant::whereIn('event_id', $eventIds);

            $stats = [
                'total_users' => $participants->distinct('user_id')->count(),
                'total_events' => $organizerEvents->count(),
                'total_categories' => Category::count(),
                'total_participants' => $participants->count(),
                'active_events' => Event::where('user_id', $organizerId)
                    ->where('start_date', '>', now())
                    ->count(),
                'completed_events' => Event::where('user_id', $organizerId)
                    ->where('end_date', '<', now())
                    ->count(),
                'this_month_events' => Event::where('user_id', $organizerId)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'this_month_participants' => EventParticipant::whereIn('event_id', $eventIds)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
            ];

            $recentActivities = $this->getRecentActivities($organizerId, $eventIds);
            $monthlyEvents = $this->getMonthlyEventData($organizerId);
            $categoryStats = $this->getCategoryStats($organizerId);
            $topEvents = $this->getTopEvents($organizerId);

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'recent_activities' => $recentActivities,
                    'monthly_events' => $monthlyEvents,
                    'category_stats' => $categoryStats,
                    'top_events' => $topEvents
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activities for organizer's events
     */
    private function getRecentActivities($organizerId, $eventIds)
    {
        $activities = collect();

        // Recent participants
        $recentParticipants = EventParticipant::with('user', 'event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(5)
            ->get();
        foreach ($recentParticipants as $participant) {
            $activities->push([
                'type' => 'user_joined',
                'message' => "User {$participant->user->full_name} joined '{$participant->event->title}'",
                'time' => $participant->created_at, // ⬅️ Objek Carbon (bukan string)
                'icon' => 'user-plus',
                'color' => 'green'
            ]);
        }

        // Recent events
        $recentEvents = Event::where('user_id', $organizerId)->latest()->take(5)->get();
        foreach ($recentEvents as $event) {
            $activities->push([
                'type' => 'event_created',
                'message' => "Event '{$event->title}' created",
                'time' => $event->created_at, // ⬅️ Objek Carbon
                'icon' => 'calendar-plus',
                'color' => 'blue'
            ]);
        }

        // Recent feedbacks
        $recentFeedbacks = Feedback::with('user', 'event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(5)
            ->get();
        foreach ($recentFeedbacks as $feedback) {
            $activities->push([
                'type' => 'feedback_submitted',
                'message' => "Feedback submitted for '{$feedback->event->title}' by {$feedback->user->full_name}",
                'time' => $feedback->created_at, // ⬅️ Objek Carbon
                'icon' => 'message-square',
                'color' => 'yellow'
            ]);
        }

        return $activities->sortByDesc('time')->take(10)->values();
    }

    /**
     * Get monthly event data (last 12 months) for organizer
     */
    private function getMonthlyEventData($organizerId)
    {
        $months = [];
        $events = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $events[] = Event::where('user_id', $organizerId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'months' => $months,
            'events' => $events
        ];
    }

    /**
     * Get category statistics for organizer's events
     */
    private function getCategoryStats($organizerId)
    {
        return Category::withCount(['events' => function($query) use ($organizerId) {
            $query->where('user_id', $organizerId);
        }])
        ->having('events_count', '>', 0)
        ->orderBy('events_count', 'desc')
        ->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'count' => $category->events_count,
                'color' => $category->color ?? '#3B82F6'
            ];
        });
    }

    /**
     * Get top events by participants for organizer
     */
    private function getTopEvents($organizerId)
    {
        return Event::where('user_id', $organizerId)
            ->withCount('participants')
            ->with(['organizer:id,full_name', 'category:id,name,color'])
            ->orderBy('participants_count', 'desc')
            ->take(10)
            ->get(); // ⬅️ Jangan map, biarkan sebagai Eloquent collection
    }
}