<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiUrl = config('services.groq.api_url', 'https://api.groq.com/openai/v1/chat/completions');
        $this->apiKey = config('services.groq.api_key');
        $this->model = config('services.groq.model', 'llama-3.1-8b-instant');
    }
    public function generateFeedbackSummary(array $feedbacks): ?string
    {
        try {
            $prompt = $this->buildFeedbackPrompt($feedbacks);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])
            ->timeout(45)
            ->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 800,
            ]);

            // Debug jika gagal
            if (!$response->successful()) {
                return "API ERROR: " . $response->body();
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'] ?? null;

        } catch (\Exception $e) {
            return "EXCEPTION: " . $e->getMessage();
        }
    }


    private function buildFeedbackPrompt(array $feedbacks): string
    {
        $feedbackData = '';
        $totalRating = 0;
        $ratingCounts = [1=>0,2=>0,3=>0,4=>0,5=>0];

        foreach ($feedbacks as $index => $feedback) {
            $rating = $feedback['rating'];
            $comment = $feedback['comment'];

            $totalRating += $rating;
            $ratingCounts[$rating]++;

            $feedbackData .= "Feedback " . ($index + 1) . ":\n";
            $feedbackData .= "Rating: {$rating}/5\n";
            $feedbackData .= "Comment: {$comment}\n\n";
        }

        $averageRating = round($totalRating / count($feedbacks), 1);

        return "
You are an expert event analyst. Analyze the following event feedback and produce a professional summary.

Event Feedback Data:
Total Feedbacks: " . count($feedbacks) . "
Average Rating: {$averageRating}/5
Rating Distribution:
  1 star: {$ratingCounts[1]}
  2 stars: {$ratingCounts[2]}
  3 stars: {$ratingCounts[3]}
  4 stars: {$ratingCounts[4]}
  5 stars: {$ratingCounts[5]}

Feedback Details:
{$feedbackData}

Please summarize in 3–5 sentences:
1. Overall sentiment and satisfaction level  
2. Key positive highlights  
3. Main concerns or issues  
4. Actionable suggestions for future improvement  

Write in a professional tone.
        ";
    }
}
