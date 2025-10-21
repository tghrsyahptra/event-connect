<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\EventParticipant;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        // Role validation is handled by middleware in routes
    }

    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30'); // Default to last 30 days
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        // Basic statistics
        $stats = $this->getBasicStats($startDate, $endDate);
        
        // User analytics
        $userAnalytics = $this->getUserAnalytics($startDate, $endDate);
        
        // Event analytics
        $eventAnalytics = $this->getEventAnalytics($startDate, $endDate);
        
        // Revenue analytics
        $revenueAnalytics = $this->getRevenueAnalytics($startDate, $endDate);
        
        // Category analytics
        $categoryAnalytics = $this->getCategoryAnalytics($startDate, $endDate);
        
        // Monthly trends
        $monthlyTrends = $this->getMonthlyTrends();
        
        // Top events
        $topEvents = $this->getTopEvents($startDate, $endDate);

        return view('admin.analytics', compact(
            'stats',
            'userAnalytics',
            'eventAnalytics',
            'revenueAnalytics',
            'categoryAnalytics',
            'monthlyTrends',
            'topEvents',
            'dateRange'
        ));
    }

    /**
     * Get basic statistics
     */
    private function getBasicStats($startDate, $endDate)
    {
        return [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'published')->count(),
            'total_participants' => EventParticipant::count(),
            'total_revenue' => Event::sum('price'),
            'avg_rating' => Feedback::avg('rating') ?? 0,
        ];
    }

    /**
     * Get user analytics
     */
    private function getUserAnalytics($startDate, $endDate)
    {
        // User registration trends
        $userTrends = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // User roles distribution
        $roleDistribution = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        // Organizer statistics
        $organizerStats = User::where('role', 'admin') // Admin = Event Organizer
            ->withCount('events')
            ->get();
        
        $totalOrganizers = $organizerStats->count();
        $avgEventsPerOrganizer = $organizerStats->avg('events_count');
        
        $organizerStats = (object) [
            'total_organizers' => $totalOrganizers,
            'avg_events_per_organizer' => $avgEventsPerOrganizer
        ];

        return [
            'user_trends' => $userTrends,
            'role_distribution' => $roleDistribution,
            'organizer_stats' => $organizerStats,
        ];
    }

    /**
     * Get event analytics
     */
    private function getEventAnalytics($startDate, $endDate)
    {
        // Event creation trends
        $eventTrends = Event::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Event status distribution
        $statusDistribution = Event::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Events by month
        $monthlyEvents = Event::select(
            DB::raw('YEAR(start_date) as year'),
            DB::raw('MONTH(start_date) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('start_date', '>=', $startDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return [
            'event_trends' => $eventTrends,
            'status_distribution' => $statusDistribution,
            'monthly_events' => $monthlyEvents,
        ];
    }

    /**
     * Get revenue analytics
     */
    private function getRevenueAnalytics($startDate, $endDate)
    {
        // Revenue trends
        $revenueTrends = Event::select(
            DB::raw('DATE(start_date) as date'),
            DB::raw('SUM(price) as revenue')
        )
        ->where('start_date', '>=', $startDate)
        ->where('status', 'published')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Revenue by category
        $revenueByCategory = Event::join('categories', 'events.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(events.price) as revenue')
            )
            ->where('events.start_date', '>=', $startDate)
            ->where('events.status', 'published')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        return [
            'revenue_trends' => $revenueTrends,
            'revenue_by_category' => $revenueByCategory,
        ];
    }

    /**
     * Get category analytics
     */
    private function getCategoryAnalytics($startDate, $endDate)
    {
        return Category::withCount(['events' => function($query) use ($startDate, $endDate) {
            $query->where('start_date', '>=', $startDate);
        }])
        ->withCount(['events as active_events_count' => function($query) {
            $query->where('status', 'published');
        }])
        ->orderBy('events_count', 'desc')
        ->get();
    }

    /**
     * Get monthly trends
     */
    private function getMonthlyTrends()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'events' => Event::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        return $months;
    }

    /**
     * Get top events
     */
    private function getTopEvents($startDate, $endDate)
    {
        return Event::withCount('participants')
            ->where('start_date', '>=', $startDate)
            ->orderBy('participants_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $dateRange = $request->get('date_range', '30');
        
        // Implementation for exporting analytics data
        // This would generate CSV/Excel files with analytics data
        
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    /**
     * Get real-time analytics
     */
    public function realtime()
    {
        $stats = [
            'online_users' => rand(10, 50), // Mock data
            'active_events' => Event::where('status', 'published')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'today_registrations' => User::whereDate('created_at', today())->count(),
            'today_events' => Event::whereDate('created_at', today())->count(),
        ];

        return response()->json($stats);
    }
}

