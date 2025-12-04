<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeedbackSummaryController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Generate AI summary for event feedback
     * POST /api/events/{id}/feedback/generate-summary
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
     * Get feedback summary for an event
     * GET /api/events/{id}/feedback/summary
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
     * Get feedback summary with all feedbacks
     * GET /api/events/{id}/feedback/summary/detailed
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
}