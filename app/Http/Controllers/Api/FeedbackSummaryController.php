<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Feedback Summary",
 *     description="API Endpoints untuk generate dan melihat AI summary dari feedback event"
 * )
 */
class FeedbackSummaryController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * @OA\Post(
     *     path="/api/events/{event}/feedback/generate-summary",
     *     tags={"Feedback Summary"},
     *     summary="Generate AI summary untuk feedback event",
     *     description="Generate ringkasan feedback menggunakan AI. Hanya dapat dilakukan oleh Event Organizer setelah event selesai dan belum pernah generate summary sebelumnya. Minimal 1 feedback diperlukan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID Event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Summary berhasil di-generate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Feedback summary generated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="summary", type="string", example="Overall, participants enjoyed the event. The venue was praised for its accessibility and comfort..."),
     *                 @OA\Property(property="generated_at", type="string", format="date-time", example="2024-12-06T10:30:00.000000Z"),
     *                 @OA\Property(property="feedback_count", type="integer", example=15),
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Event belum selesai, summary sudah ada, atau feedback tidak mencukupi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cannot generate summary. Event has not ended yet.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Bukan organizer event",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Only event organizer can generate summary.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event tidak ditemukan"),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error - Gagal generate summary",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to generate summary. Please try again later.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function generateSummary(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user is the event organizer
        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only event organizer can generate summary.'
            ], 403);
        }

        // Check if event has ended
        if ($event->end_date > now()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot generate summary. Event has not ended yet.'
            ], 400);
        }

        // Check if summary already generated
        if ($event->feedback_summary) {
            return response()->json([
                'success' => false,
                'message' => 'Summary already generated for this event. Each event can only have one summary.'
            ], 400);
        }

        // Get all feedbacks for the event
        $feedbacks = Feedback::where('event_id', $event->id)
            ->with('user:id,name')
            ->get();

        // Check minimum feedback requirement
        if ($feedbacks->count() < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Need at least 1 feedbacks to generate summary. Current: ' . $feedbacks->count()
            ], 400);
        }

        // Prepare feedback data for AI
        $feedbackData = $feedbacks->map(function ($feedback) {
            return [
                'rating' => $feedback->rating,
                'comment' => $feedback->comment,
                'user_name' => $feedback->user->name ?? 'Anonymous'
            ];
        })->toArray();

        // Generate summary using AI
        $summary = $this->aiService->generateFeedbackSummary($feedbackData);

        if (!$summary) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate summary. Please try again later.'
            ], 500);
        }

        // Update event with generated summary
        $event->update([
            'feedback_summary' => $summary,
            'feedback_summary_generated_at' => now(),
            'feedback_count_at_summary' => $feedbacks->count()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback summary generated successfully',
            'data' => [
                'summary' => $summary,
                'generated_at' => $event->feedback_summary_generated_at,
                'feedback_count' => $feedbacks->count(),
                'average_rating' => round($feedbacks->avg('rating'), 1)
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{event}/feedback/summary",
     *     tags={"Feedback Summary"},
     *     summary="Get feedback summary untuk event",
     *     description="Mendapatkan AI-generated summary beserta statistik feedback event. Hanya dapat diakses oleh Event Organizer.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID Event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Summary berhasil didapatkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="summary", type="string", example="Overall, participants enjoyed the event. The venue was praised for its accessibility and comfort..."),
     *                 @OA\Property(property="generated_at", type="string", format="date-time", example="2024-12-06T10:30:00.000000Z"),
     *                 @OA\Property(property="feedback_count", type="integer", example=15, description="Jumlah feedback saat summary di-generate"),
     *                 @OA\Property(property="current_feedback_count", type="integer", example=20, description="Jumlah feedback saat ini"),
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="rating_distribution", type="object",
     *                     @OA\Property(property="5_star", type="integer", example=10),
     *                     @OA\Property(property="4_star", type="integer", example=5),
     *                     @OA\Property(property="3_star", type="integer", example=3),
     *                     @OA\Property(property="2_star", type="integer", example=1),
     *                     @OA\Property(property="1_star", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Bukan organizer event",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Only event organizer can view summary.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Summary tidak ditemukan atau event tidak ada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No summary available. Generate summary first.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getSummary(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user is the event organizer
        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only event organizer can view summary.'
            ], 403);
        }

        if (!$event->feedback_summary) {
            return response()->json([
                'success' => false,
                'message' => 'No summary available. Generate summary first.'
            ], 404);
        }

        // Get feedback statistics
        $feedbacks = Feedback::where('event_id', $event->id)->get();
        $ratingDistribution = $feedbacks->groupBy('rating')->map->count();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $event->feedback_summary,
                'generated_at' => $event->feedback_summary_generated_at,
                'feedback_count' => $event->feedback_count_at_summary,
                'current_feedback_count' => $feedbacks->count(),
                'average_rating' => round($feedbacks->avg('rating'), 1),
                'rating_distribution' => [
                    '5_star' => $ratingDistribution[5] ?? 0,
                    '4_star' => $ratingDistribution[4] ?? 0,
                    '3_star' => $ratingDistribution[3] ?? 0,
                    '2_star' => $ratingDistribution[2] ?? 0,
                    '1_star' => $ratingDistribution[1] ?? 0,
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{event}/feedback/summary/detailed",
     *     tags={"Feedback Summary"},
     *     summary="Get detailed feedback summary dengan semua feedback",
     *     description="Mendapatkan AI-generated summary, statistik lengkap, dan daftar semua feedback event. Hanya dapat diakses oleh Event Organizer.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID Event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detailed summary berhasil didapatkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="summary", type="string", example="Overall, participants enjoyed the event. The venue was praised for its accessibility and comfort..."),
     *                 @OA\Property(property="generated_at", type="string", format="date-time", example="2024-12-06T10:30:00.000000Z"),
     *                 @OA\Property(property="statistics", type="object",
     *                     @OA\Property(property="total_feedbacks", type="integer", example=20),
     *                     @OA\Property(property="feedback_count_at_summary", type="integer", example=15),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="rating_distribution", type="object",
     *                         @OA\Property(property="5_star", type="integer", example=10),
     *                         @OA\Property(property="4_star", type="integer", example=5),
     *                         @OA\Property(property="3_star", type="integer", example=3),
     *                         @OA\Property(property="2_star", type="integer", example=1),
     *                         @OA\Property(property="1_star", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="feedbacks", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="rating", type="integer", example=5),
     *                         @OA\Property(property="comment", type="string", example="Great event! Very informative and well organized."),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="user", type="object",
     *                             @OA\Property(property="name", type="string", example="John Doe"),
     *                             @OA\Property(property="email", type="string", example="john@example.com")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Bukan organizer event",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Only event organizer can view summary.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getDetailedSummary(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user is the event organizer
        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only event organizer can view summary.'
            ], 403);
        }

        $feedbacks = Feedback::with('user:id,name,email')
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $ratingDistribution = $feedbacks->groupBy('rating')->map->count();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $event->feedback_summary,
                'generated_at' => $event->feedback_summary_generated_at,
                'statistics' => [
                    'total_feedbacks' => $feedbacks->count(),
                    'feedback_count_at_summary' => $event->feedback_count_at_summary,
                    'average_rating' => round($feedbacks->avg('rating'), 1),
                    'rating_distribution' => [
                        '5_star' => $ratingDistribution[5] ?? 0,
                        '4_star' => $ratingDistribution[4] ?? 0,
                        '3_star' => $ratingDistribution[3] ?? 0,
                        '2_star' => $ratingDistribution[2] ?? 0,
                        '1_star' => $ratingDistribution[1] ?? 0,
                    ]
                ],
                'feedbacks' => $feedbacks->map(function ($feedback) {
                    return [
                        'id' => $feedback->id,
                        'rating' => $feedback->rating,
                        'comment' => $feedback->comment,
                        'created_at' => $feedback->created_at,
                        'user' => [
                            'name' => $feedback->user->name,
                            'email' => $feedback->user->email
                        ]
                    ];
                })
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{event}/feedback/summary",
     *     tags={"Feedback Summary"},
     *     summary="Update/regenerate feedback summary",
     *     description="Regenerate AI summary dengan feedback terbaru. Hanya dapat dilakukan oleh Event Organizer. Summary yang lama akan diganti dengan yang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID Event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Summary berhasil di-update",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Feedback summary updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="summary", type="string", example="Overall, participants enjoyed the event. The venue was praised for its accessibility and comfort..."),
     *                 @OA\Property(property="generated_at", type="string", format="date-time", example="2024-12-06T10:30:00.000000Z"),
     *                 @OA\Property(property="feedback_count", type="integer", example=20),
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="previous_feedback_count", type="integer", example=15, description="Jumlah feedback pada summary sebelumnya")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Feedback tidak mencukupi atau belum ada summary",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No existing summary to update. Please generate summary first.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Bukan organizer event",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Only event organizer can update summary.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event tidak ditemukan"),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error - Gagal update summary",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update summary. Please try again later.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function updateSummary(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user is the event organizer
        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only event organizer can update summary.'
            ], 403);
        }

        // Check if summary exists
        if (!$event->feedback_summary) {
            return response()->json([
                'success' => false,
                'message' => 'No existing summary to update. Please generate summary first.'
            ], 400);
        }

        // Get all feedbacks for the event
        $feedbacks = Feedback::where('event_id', $event->id)
            ->with('user:id,name')
            ->get();

        // Check minimum feedback requirement
        if ($feedbacks->count() < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Need at least 1 feedbacks to update summary. Current: ' . $feedbacks->count()
            ], 400);
        }

        // Prepare feedback data for AI
        $feedbackData = $feedbacks->map(function ($feedback) {
            return [
                'rating' => $feedback->rating,
                'comment' => $feedback->comment,
                'user_name' => $feedback->user->name ?? 'Anonymous'
            ];
        })->toArray();

        // Generate new summary using AI
        $summary = $this->aiService->generateFeedbackSummary($feedbackData);

        if (!$summary) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update summary. Please try again later.'
            ], 500);
        }

        $previousFeedbackCount = $event->feedback_count_at_summary;

        // Update event with new summary
        $event->update([
            'feedback_summary' => $summary,
            'feedback_summary_generated_at' => now(),
            'feedback_count_at_summary' => $feedbacks->count()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback summary updated successfully',
            'data' => [
                'summary' => $summary,
                'generated_at' => $event->feedback_summary_generated_at,
                'feedback_count' => $feedbacks->count(),
                'average_rating' => round($feedbacks->avg('rating'), 1),
                'previous_feedback_count' => $previousFeedbackCount
            ]
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{event}/feedback/summary",
     *     tags={"Feedback Summary"},
     *     summary="Hapus feedback summary",
     *     description="Menghapus AI-generated summary dari event. Hanya dapat dilakukan oleh Event Organizer. Summary dapat di-generate ulang setelah dihapus.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         description="ID Event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Summary berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Feedback summary deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Tidak ada summary untuk dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No summary to delete.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Bukan organizer event",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Only event organizer can delete summary.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function deleteSummary(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        // Check if user is the event organizer
        if ($event->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only event organizer can delete summary.'
            ], 403);
        }

        // Check if summary exists
        if (!$event->feedback_summary) {
            return response()->json([
                'success' => false,
                'message' => 'No summary to delete.'
            ], 400);
        }

        // Delete summary
        $event->update([
            'feedback_summary' => null,
            'feedback_summary_generated_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback summary deleted successfully'
        ], 200);
    }
}