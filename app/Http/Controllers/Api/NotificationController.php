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

/**
 * @OA\Tag(
 *     name="Notification",
 *     description="API Endpoints untuk manajemen notifikasi"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     tags={"Notification"},
     *     summary="Get daftar notifikasi user",
     *     description="Mendapatkan semua notifikasi user dengan pagination",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="unread_only",
     *         in="query",
     *         description="Filter hanya notifikasi yang belum dibaca",
     *         required=false,
     *         @OA\Schema(type="boolean", default=false)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Nomor halaman",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar notifikasi berhasil didapatkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="type", type="string", example="event_reminder"),
     *                         @OA\Property(property="title", type="string", example="Event Reminder"),
     *                         @OA\Property(property="message", type="string", example="Reminder: Tech Conference 2024 on 15 Dec 2024"),
     *                         @OA\Property(property="is_read", type="boolean", example=false),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="event", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Tech Conference 2024")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Post(
     *     path="/api/notifications/{notification}/read",
     *     tags={"Notification"},
     *     summary="Mark notifikasi sebagai sudah dibaca",
     *     description="Menandai satu notifikasi sebagai sudah dibaca",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="notification",
     *         in="path",
     *         description="ID Notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifikasi berhasil ditandai sebagai sudah dibaca",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification marked as read")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Unauthorized - Bukan notifikasi user"),
     *     @OA\Response(response=404, description="Notifikasi tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Post(
     *     path="/api/notifications/mark-all-read",
     *     tags={"Notification"},
     *     summary="Mark semua notifikasi sebagai sudah dibaca",
     *     description="Menandai semua notifikasi user sebagai sudah dibaca",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Semua notifikasi berhasil ditandai sebagai sudah dibaca",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="All notifications marked as read")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Get(
     *     path="/api/notifications/unread-count",
     *     tags={"Notification"},
     *     summary="Get jumlah notifikasi yang belum dibaca",
     *     description="Mendapatkan total notifikasi yang belum dibaca oleh user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Jumlah notifikasi unread berhasil didapatkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="unread_count", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Delete(
     *     path="/api/notifications/{notification}",
     *     tags={"Notification"},
     *     summary="Hapus notifikasi",
     *     description="Menghapus satu notifikasi",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="notification",
     *         in="path",
     *         description="ID Notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifikasi berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Unauthorized - Bukan notifikasi user"),
     *     @OA\Response(response=404, description="Notifikasi tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Post(
     *     path="/api/notifications/send-event-reminder",
     *     tags={"Notification"},
     *     summary="Kirim email reminder event (Manual/Testing)",
     *     description="Mengirim email reminder ke participant event secara manual. Untuk testing atau admin trigger",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id"},
     *             @OA\Property(property="event_id", type="integer", example=1, description="ID Event"),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email reminder berhasil dikirim",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event reminders sent successfully to 10 participant(s)")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event atau participant tidak ditemukan"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error saat mengirim email"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function sendEventReminder(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        try {
            $event = Event::findOrFail($request->event_id);

            if ($request->has('user_id')) {
                $participant = EventParticipant::where('event_id', $event->id)
                    ->where('user_id', $request->user_id)
                    ->whereIn('status', ['registered'])
                    ->with('user')
                    ->firstOrFail();

                Mail::to($participant->user->email)
                    ->send(new EventReminderMail($event, $participant->user));

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
     * @OA\Get(
     *     path="/api/notifications/upcoming-reminders",
     *     tags={"Notification"},
     *     summary="Get upcoming events yang butuh reminder",
     *     description="Mendapatkan daftar event yang akan datang dalam 7 hari ke depan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar upcoming events berhasil didapatkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="event_id", type="integer", example=1),
     *                     @OA\Property(property="event_title", type="string", example="Tech Conference 2024"),
     *                     @OA\Property(property="start_date", type="string", format="date-time"),
     *                     @OA\Property(property="end_date", type="string", format="date-time"),
     *                     @OA\Property(property="days_until_event", type="integer", example=3),
     *                     @OA\Property(property="location", type="string", example="Jakarta Convention Center"),
     *                     @OA\Property(property="event_type", type="string", example="online"),
     *                     @OA\Property(property="contact_info", type="string", example="info@techconf.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getUpcomingReminders(Request $request): JsonResponse
    {
        $user = $request->user();

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