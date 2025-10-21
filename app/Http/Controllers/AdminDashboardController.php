<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\EventParticipant;
use App\Models\Feedback;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        // Role validation is handled by middleware in routes
    }
    public function index()
    {
        // Get basic statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get monthly event data for chart
        $monthlyEvents = $this->getMonthlyEventData();
        
        // Get category distribution
        $categoryStats = $this->getCategoryStats();
        
        // Get user registration trends
        $userTrends = $this->getUserTrends();
        
        // Get top events by participants
        $topEvents = $this->getTopEvents();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentActivities', 
            'monthlyEvents', 
            'categoryStats', 
            'userTrends', 
            'topEvents'
        ));
    }

    public function users()
    {
        return redirect()->route('admin.users.index');
    }

    public function events()
    {
        return redirect()->route('admin.events.index');
    }

    public function categories()
    {
        return redirect()->route('admin.categories.index');
    }

    public function analytics()
    {
        return redirect()->route('admin.analytics');
    }
    
    private function getDashboardStats()
    {
        $organizerId = auth()->id();
        
        // Get organizer's events
        $organizerEvents = Event::where('user_id', $organizerId);
        
        // Get participants from organizer's events
        $eventIds = $organizerEvents->pluck('id');
        $participants = EventParticipant::whereIn('event_id', $eventIds);
        
        return [
            'total_users' => $participants->distinct('user_id')->count(), // Users who joined organizer's events
            'total_events' => $organizerEvents->count(),
            'total_categories' => Category::count(), // Categories are global
            'total_participants' => $participants->count(),
            'total_feedbacks' => Feedback::whereIn('event_id', $eventIds)->count(),
            'active_events' => $organizerEvents->where('start_date', '>', now())->count(),
            'completed_events' => $organizerEvents->where('end_date', '<', now())->count(),
            'organizers' => 1, // Current organizer only
            'this_month_events' => $organizerEvents->whereMonth('created_at', now()->month)->count(),
            'this_month_participants' => $participants->whereMonth('created_at', now()->month)->count(),
        ];
    }
    
    private function getRecentActivities()
    {
        $organizerId = auth()->id();
        $activities = collect();
        
        // Get organizer's events
        $organizerEvents = Event::where('user_id', $organizerId);
        $eventIds = $organizerEvents->pluck('id');
        
        // Recent participants who joined organizer's events
        $recentParticipants = EventParticipant::with('user', 'event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(5)
            ->get();
        foreach ($recentParticipants as $participant) {
            $activities->push([
                'type' => 'user_joined',
                'message' => "User {$participant->user->full_name} joined '{$participant->event->title}'",
                'time' => $participant->created_at,
                'icon' => 'user-plus',
                'color' => 'green'
            ]);
        }
        
        // Recent events created by organizer
        $recentEvents = $organizerEvents->latest()->take(5)->get();
        foreach ($recentEvents as $event) {
            $activities->push([
                'type' => 'event_created',
                'message' => "Event '{$event->title}' created",
                'time' => $event->created_at,
                'icon' => 'calendar-plus',
                'color' => 'blue'
            ]);
        }
        
        // Recent feedbacks on organizer's events
        $recentFeedbacks = Feedback::with('user', 'event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->take(5)
            ->get();
        foreach ($recentFeedbacks as $feedback) {
            $activities->push([
                'type' => 'feedback_submitted',
                'message' => "Feedback submitted for '{$feedback->event->title}' by {$feedback->user->full_name}",
                'time' => $feedback->created_at,
                'icon' => 'message-square',
                'color' => 'yellow'
            ]);
        }
        
        return $activities->sortByDesc('time')->take(10);
    }
    
    private function getMonthlyEventData()
    {
        $months = [];
        $eventCounts = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $eventCounts[] = Event::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return [
            'months' => $months,
            'events' => $eventCounts
        ];
    }
    
    private function getCategoryStats()
    {
        return Category::withCount('events')
            ->orderBy('events_count', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->events_count,
                    'color' => $category->color ?? '#3B82F6'
                ];
            });
    }
    
    private function getUserTrends()
    {
        $trends = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trends[] = [
                'date' => $date->format('M d'),
                'users' => User::whereDate('created_at', $date)->count(),
                'events' => Event::whereDate('created_at', $date)->count()
            ];
        }
        
        return $trends;
    }
    
    private function getTopEvents()
    {
        return Event::withCount('participants')
            ->with('organizer', 'category')
            ->orderBy('participants_count', 'desc')
            ->take(10)
            ->get();
    }
    
}
