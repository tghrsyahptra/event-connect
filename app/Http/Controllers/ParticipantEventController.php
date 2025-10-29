<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantEventController extends Controller
{
    /**
     * Display events with search and filter
     */
    public function index(Request $request): View
    {
        $query = Event::with(['organizer', 'category'])
            ->published()
            ->active()
            ->upcoming()
            ->open();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('organizer', function($organizerQuery) use ($searchTerm) {
                      $organizerQuery->where('name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('full_name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        $events = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        $priceRanges = [
            ['min' => 0, 'max' => 0, 'label' => 'Free'],
            ['min' => 1, 'max' => 100000, 'label' => 'Under 100K'],
            ['min' => 100000, 'max' => 500000, 'label' => '100K - 500K'],
            ['min' => 500000, 'max' => 1000000, 'label' => '500K - 1M'],
            ['min' => 1000000, 'max' => null, 'label' => 'Above 1M'],
        ];

        $dateRanges = [
            ['key' => 'today', 'label' => 'Today'],
            ['key' => 'tomorrow', 'label' => 'Tomorrow'],
            ['key' => 'this_week', 'label' => 'This Week'],
            ['key' => 'next_week', 'label' => 'Next Week'],
            ['key' => 'this_month', 'label' => 'This Month'],
            ['key' => 'next_month', 'label' => 'Next Month'],
        ];

        $sortOptions = [
            ['key' => 'start_date', 'label' => 'Date (Earliest First)', 'order' => 'asc'],
            ['key' => 'start_date', 'label' => 'Date (Latest First)', 'order' => 'desc'],
            ['key' => 'price', 'label' => 'Price (Low to High)', 'order' => 'asc'],
            ['key' => 'price', 'label' => 'Price (High to Low)', 'order' => 'desc'],
            ['key' => 'title', 'label' => 'Title (A-Z)', 'order' => 'asc'],
            ['key' => 'title', 'label' => 'Title (Z-A)', 'order' => 'desc'],
            ['key' => 'popularity', 'label' => 'Most Popular', 'order' => 'desc'],
            ['key' => 'created_at', 'label' => 'Newest First', 'order' => 'desc'],
        ];

        return view('participant.events.index', compact(
            'events', 
            'categories', 
            'priceRanges', 
            'dateRanges', 
            'sortOptions'
        ));
    }

    /**
     * Show event details
     */
    public function show(Event $event): View
    {
        $event->load(['organizer', 'category', 'participants.user']);
        
        // Check if user is already participating
        $isParticipating = false;
        $userParticipation = null;
        
        if (auth()->check()) {
            $userParticipation = $event->participants()
                ->where('user_id', auth()->id())
                ->first();
            $isParticipating = $userParticipation !== null;
        }

        return view('participant.events.show', compact('event', 'isParticipating', 'userParticipation'));
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, Request $request): void
    {
        // Category filters
        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $query->whereIn('category_id', $request->category_ids);
        } elseif ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Price filters
        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // Date filters
        if ($request->has('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        // Location filter
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Paid/Free filter
        if ($request->has('is_paid')) {
            $query->where('is_paid', $request->boolean('is_paid'));
        }

        // Availability filter
        if ($request->has('available_only') && $request->boolean('available_only')) {
            $query->whereRaw('quota > registered_count');
        }

        // Quick date filters
        if ($request->has('date_filter')) {
            $this->applyDateFilter($query, $request->date_filter);
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, Request $request): void
    {
        $sortBy = $request->get('sort_by', 'start_date');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            case 'popularity':
                $query->orderBy('registered_count', 'desc');
                break;
            default:
                $query->orderBy('start_date', $sortOrder);
        }
    }

    /**
     * Apply quick date filters
     */
    private function applyDateFilter($query, string $dateFilter): void
    {
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('start_date', today());
                break;
            case 'tomorrow':
                $query->whereDate('start_date', today()->addDay());
                break;
            case 'this_week':
                $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'next_week':
                $query->whereBetween('start_date', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            case 'next_month':
                $query->whereBetween('start_date', [now()->addMonth()->startOfMonth(), now()->addMonth()->endOfMonth()]);
                break;
        }
    }
}




