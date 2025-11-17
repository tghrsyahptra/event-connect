<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Mail\EventReminderMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Notification::with(['event'])
            ->where('user_id', $user->id);

        if ($request->has('unread_only') && $request->boolean('unread_only')) {
            $query->unread();
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        $user = $request->user();

        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to mark this notification'
            ], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notification count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $user = request()->user();

        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this notification'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Send event reminder email manually (for testing or admin trigger)
     */
    public function sendEventReminder(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'sometimes|exists:users,id', // Optional, if not provided, send to all participants
        ]);

        try {
            $event = Event::findOrFail($request->event_id);

            // If specific user_id provided, send only to that user
            if ($request->has('user_id')) {
                $participant = EventParticipant::where('event_id', $event->id)
                    ->where('user_id', $request->user_id)
                    ->whereIn('status', ['registered'])
                    ->with('user')
                    ->firstOrFail();

                Mail::to($participant->user->email)
                    ->send(new EventReminderMail($event, $participant->user));

                // Create notification
                Notification::create([
                    'user_id' => $participant->user_id,
                    'event_id' => $event->id,
                    'type' => 'event_reminder_manual',
                    'title' => 'Event Reminder',
                    'message' => "Reminder: {$event->title} on {$event->start_date->format('d M Y, H:i')}",
                    'is_read' => false,
                    'data' => [
                        'event_title' => $event->title,
                        'start_date' => $event->start_date->toDateTimeString(),
                        'end_date' => $event->end_date->toDateTimeString(),
                        'location' => $event->location,
                        'event_type' => $event->event_type,
                        'contact_info' => $event->contact_info,
                    ],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Event reminder sent successfully to ' . $participant->user->email
                ]);
            }

            // Send to all participants
            $participants = EventParticipant::where('event_id', $event->id)
                ->whereIn('status', ['registered'])
                ->with('user')
                ->get();

            if ($participants->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No participants found for this event'
                ], 404);
            }

            $sentCount = 0;
            foreach ($participants as $participant) {
                Mail::to($participant->user->email)
                    ->send(new EventReminderMail($event, $participant->user));

                // Create notification
                Notification::create([
                    'user_id' => $participant->user_id,
                    'event_id' => $event->id,
                    'type' => 'event_reminder_manual',
                    'title' => 'Event Reminder',
                    'message' => "Reminder: {$event->title} on {$event->start_date->format('d M Y, H:i')}",
                    'is_read' => false,
                    'data' => [
                        'event_title' => $event->title,
                        'start_date' => $event->start_date->toDateTimeString(),
                        'end_date' => $event->end_date->toDateTimeString(),
                        'location' => $event->location,
                        'event_type' => $event->event_type,
                        'contact_info' => $event->contact_info,
                    ],
                ]);

                $sentCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Event reminders sent successfully to {$sentCount} participant(s)"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send event reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming events that need reminders
     */
    public function getUpcomingReminders(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get user's upcoming events (within next 7 days)
        $upcomingEvents = EventParticipant::where('user_id', $user->id)
            ->whereIn('status', ['registered'])
            ->whereHas('event', function($query) {
                $query->where('start_date', '>=', Carbon::now())
                    ->where('start_date', '<=', Carbon::now()->addDays(7))
                    ->where('status', 'published')
                    ->where('is_active', true);
            })
            ->with('event')
            ->get()
            ->map(function($participant) {
                return [
                    'event_id' => $participant->event->id,
                    'event_title' => $participant->event->title,
                    'start_date' => $participant->event->start_date,
                    'end_date' => $participant->event->end_date,
                    'days_until_event' => Carbon::now()->diffInDays($participant->event->start_date, false),
                    'location' => $participant->event->location,
                    'event_type' => $participant->event->event_type,
                    'contact_info' => $participant->event->contact_info,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $upcomingEvents
        ]);
    }
}