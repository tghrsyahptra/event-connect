<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Get all event organizers (admins) with their events
     */
    public function getOrganizers(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $status = $request->get('status'); // active, inactive
        
        // Build query for organizers (users with admin role or is_organizer = true)
        $query = User::where(function($q) {
            $q->where('role', 'admin')
              ->orWhere('is_organizer', true);
        })->with(['events' => function($eventQuery) {
            $eventQuery->with(['category', 'participants'])
                      ->orderBy('created_at', 'desc');
        }]);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter
        if ($status === 'active') {
            $query->where('is_organizer', true);
        } elseif ($status === 'inactive') {
            $query->where('is_organizer', false);
        }

        $organizers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform data for response
        $transformedOrganizers = $organizers->getCollection()->map(function ($organizer) {
            $events = $organizer->events;
            
            return [
                'id' => $organizer->id,
                'name' => $organizer->full_name ?? $organizer->name,
                'email' => $organizer->email,
                'phone' => $organizer->phone,
                'bio' => $organizer->bio,
                'avatar' => $organizer->avatar,
                'role' => $organizer->role,
                'is_organizer' => $organizer->is_organizer,
                'created_at' => $organizer->created_at,
                'updated_at' => $organizer->updated_at,
                'events' => [
                    'total' => $events->count(),
                    'published' => $events->where('status', 'published')->count(),
                    'draft' => $events->where('status', 'draft')->count(),
                    'completed' => $events->where('status', 'completed')->count(),
                    'cancelled' => $events->where('status', 'cancelled')->count(),
                    'total_participants' => $events->sum('registered_count'),
                    'total_revenue' => $events->where('is_paid', true)->sum('price'),
                    'recent_events' => $events->take(5)->map(function ($event) {
                        return [
                            'id' => $event->id,
                            'title' => $event->title,
                            'status' => $event->status,
                            'start_date' => $event->start_date,
                            'location' => $event->location,
                            'price' => $event->price,
                            'is_paid' => $event->is_paid,
                            'registered_count' => $event->registered_count,
                            'quota' => $event->quota,
                            'category' => [
                                'id' => $event->category->id,
                                'name' => $event->category->name,
                                'color' => $event->category->color,
                            ],
                            'created_at' => $event->created_at,
                        ];
                    })
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'organizers' => $transformedOrganizers,
                'pagination' => [
                    'current_page' => $organizers->currentPage(),
                    'last_page' => $organizers->lastPage(),
                    'per_page' => $organizers->perPage(),
                    'total' => $organizers->total(),
                    'from' => $organizers->firstItem(),
                    'to' => $organizers->lastItem(),
                    'has_more_pages' => $organizers->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * Get all events from all organizers
     */
    public function getAllEvents(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $status = $request->get('status');
        $category = $request->get('category_id');
        $organizer = $request->get('organizer_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query for all events
        $query = Event::with(['organizer', 'category', 'participants'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhereHas('organizer', function($organizerQuery) use ($search) {
                      $organizerQuery->where('name', 'like', '%' . $search . '%')
                                   ->orWhere('full_name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($organizer) {
            $query->where('user_id', $organizer);
        }

        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('start_date', '<=', $dateTo);
        }

        $events = $query->paginate($perPage);

        // Transform data for response
        $transformedEvents = $events->getCollection()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'price' => $event->price,
                'is_paid' => $event->is_paid,
                'quota' => $event->quota,
                'registered_count' => $event->registered_count,
                'status' => $event->status,
                'is_active' => $event->is_active,
                'image' => $event->image,
                'qr_code' => $event->qr_code,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
                'organizer' => [
                    'id' => $event->organizer->id,
                    'name' => $event->organizer->full_name ?? $event->organizer->name,
                    'email' => $event->organizer->email,
                    'phone' => $event->organizer->phone,
                    'role' => $event->organizer->role,
                ],
                'category' => [
                    'id' => $event->category->id,
                    'name' => $event->category->name,
                    'color' => $event->category->color,
                ],
                'participants' => [
                    'total' => $event->participants->count(),
                    'attended' => $event->participants->where('status', 'attended')->count(),
                    'registered' => $event->participants->where('status', 'registered')->count(),
                    'cancelled' => $event->participants->where('status', 'cancelled')->count(),
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'events' => $transformedEvents,
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'from' => $events->firstItem(),
                    'to' => $events->lastItem(),
                    'has_more_pages' => $events->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * Get statistics overview
     */
    public function getStatistics(Request $request): JsonResponse
    {
        // Get date range (default: last 12 months)
        $dateFrom = $request->get('date_from', now()->subMonths(12)->startOfMonth());
        $dateTo = $request->get('date_to', now()->endOfMonth());

        // Base queries
        $eventsQuery = Event::whereBetween('created_at', [$dateFrom, $dateTo]);
        $organizersQuery = User::where(function($q) {
            $q->where('role', 'admin')->orWhere('is_organizer', true);
        })->whereBetween('created_at', [$dateFrom, $dateTo]);
        $participantsQuery = EventParticipant::whereBetween('created_at', [$dateFrom, $dateTo]);

        // Overall statistics
        $statistics = [
            'total_organizers' => User::where(function($q) {
                $q->where('role', 'admin')->orWhere('is_organizer', true);
            })->count(),
            'total_events' => Event::count(),
            'total_participants' => EventParticipant::count(),
            'total_revenue' => Event::where('is_paid', true)->sum('price'),
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo
            ]
        ];

        // Period statistics
        $periodStats = [
            'organizers' => $organizersQuery->count(),
            'events' => $eventsQuery->count(),
            'participants' => $participantsQuery->count(),
            'revenue' => $eventsQuery->where('is_paid', true)->sum('price'),
        ];

        // Event status breakdown
        $eventStatusBreakdown = Event::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        // Monthly trends
        $monthlyTrends = Event::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top organizers by event count
        $topOrganizers = User::where(function($q) {
            $q->where('role', 'admin')->orWhere('is_organizer', true);
        })
        ->withCount('events')
        ->orderBy('events_count', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($organizer) {
            return [
                'id' => $organizer->id,
                'name' => $organizer->full_name ?? $organizer->name,
                'email' => $organizer->email,
                'events_count' => $organizer->events_count,
            ];
        });

        // Category breakdown
        $categoryBreakdown = DB::table('events')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(events.id) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $statistics,
                'period_statistics' => $periodStats,
                'event_status_breakdown' => $eventStatusBreakdown,
                'monthly_trends' => $monthlyTrends,
                'top_organizers' => $topOrganizers,
                'category_breakdown' => $categoryBreakdown,
            ]
        ]);
    }

    /**
     * Toggle organizer status
     */
    public function toggleOrganizerStatus(Request $request, User $user): JsonResponse
    {
        // Only allow toggling for admin users, not super_admin
        if ($user->role === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify super admin status'
            ], 400);
        }

        $user->update([
            'is_organizer' => !$user->is_organizer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Organizer status updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name ?? $user->name,
                    'email' => $user->email,
                    'is_organizer' => $user->is_organizer,
                ]
            ]
        ]);
    }

    /**
     * Get organizer details with all events
     */
    public function getOrganizerDetails(Request $request, User $organizer): JsonResponse
    {
        // Verify this is an organizer
        if (!$organizer->isOrganizer()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an organizer'
            ], 400);
        }

        $events = $organizer->events()
            ->with(['category', 'participants'])
            ->orderBy('created_at', 'desc')
            ->get();

        $organizerData = [
            'id' => $organizer->id,
            'name' => $organizer->full_name ?? $organizer->name,
            'email' => $organizer->email,
            'phone' => $organizer->phone,
            'bio' => $organizer->bio,
            'avatar' => $organizer->avatar,
            'role' => $organizer->role,
            'is_organizer' => $organizer->is_organizer,
            'created_at' => $organizer->created_at,
            'events' => $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'location' => $event->location,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'price' => $event->price,
                    'is_paid' => $event->is_paid,
                    'quota' => $event->quota,
                    'registered_count' => $event->registered_count,
                    'status' => $event->status,
                    'is_active' => $event->is_active,
                    'category' => [
                        'id' => $event->category->id,
                        'name' => $event->category->name,
                        'color' => $event->category->color,
                    ],
                    'participants' => [
                        'total' => $event->participants->count(),
                        'attended' => $event->participants->where('status', 'attended')->count(),
                        'registered' => $event->participants->where('status', 'registered')->count(),
                        'cancelled' => $event->participants->where('status', 'cancelled')->count(),
                    ],
                    'created_at' => $event->created_at,
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $organizerData
        ]);
    }
}