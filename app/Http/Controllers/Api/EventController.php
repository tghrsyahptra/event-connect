<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    /**
     * Get all published events (Homepage)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['organizer', 'category'])
            ->published()
            ->active()
            ->upcoming()
            ->open();

        // Search by name
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by paid/free
        if ($request->has('is_paid')) {
            $query->where('is_paid', $request->boolean('is_paid'));
        }

        // Filter by date
        if ($request->has('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get event details
     */
    public function show(Event $event): JsonResponse
    {
        $event->load(['organizer', 'category', 'participants']);

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Create new event (Organizer only)
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->is_organizer) {
            return response()->json([
                'success' => false,
                'message' => 'Only organizers can create events'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'is_paid' => 'boolean',
            'price' => 'required_if:is_paid,true|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['user_id'] = $user->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        // Generate QR code
        $data['qr_code'] = 'event_' . time() . '_' . uniqid();

        $event = Event::create($data);

        // Generate QR code image
        $qrCodePath = 'qr_codes/' . $event->qr_code . '.png';
        QrCode::format('png')->size(200)->generate($event->qr_code, storage_path('app/public/' . $qrCodePath));
        $event->update(['qr_code' => $qrCodePath]);

        $event->load(['organizer', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * Update event (Organizer only)
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this event'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date|after:now',
            'end_date' => 'sometimes|required|date|after:start_date',
            'is_paid' => 'sometimes|boolean',
            'price' => 'required_if:is_paid,true|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:draft,published,cancelled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);
        $event->load(['organizer', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * Delete event (Organizer only)
     */
    public function destroy(Event $event): JsonResponse
    {
        $user = request()->user();

        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this event'
            ], 403);
        }

        // Delete associated files
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        if ($event->qr_code) {
            Storage::disk('public')->delete($event->qr_code);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * Get user's events (My Events)
     */
    public function myEvents(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Event::with(['organizer', 'category'])
            ->where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get events user is participating in
     */
    public function participatingEvents(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Event::with(['organizer', 'category'])
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        if ($request->has('status')) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->where('end_date', '<', now());
            }
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get categories for filtering
     */
    public function categories(): JsonResponse
    {
        $categories = Category::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
