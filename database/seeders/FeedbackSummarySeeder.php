<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Feedback;
use App\Models\EventParticipant;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class FeedbackSummarySeeder extends Seeder
{
    public function run(): void
    {
        // Ensure category exists
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Technology',
                'description' => 'Technology and innovation events',
                'color' => '#3B82F6',
                'is_active' => true,
            ]);
            $this->command->info('✓ Category created');
        }

        // Create or get organizer
        $organizer = User::where('email', 'organizer@example.com')->first();
        if (!$organizer) {
            $organizer = User::create([
                'name' => 'Event Organizer Test',
                'full_name' => 'Event Organizer Test',
                'email' => 'organizer@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin', // Sesuai dengan DummyDataSeeder
                'phone' => '+6281234567800',
                'bio' => 'Event organizer for testing feedback summary',
                'avatar' => null,
            ]);
            $this->command->info('✓ Organizer created with ID: ' . $organizer->id);
        } else {
            $this->command->info('✓ Organizer already exists with ID: ' . $organizer->id);
        }

        // Create or get event
        $event = Event::where('title', 'Tech Conference 2024')->where('user_id', $organizer->id)->first();

        if (!$event) {
            $event = Event::create([
                'user_id' => $organizer->id, // PENTING: pastikan ini ID organizer
                'category_id' => $category->id,
                'title' => 'Tech Conference 2024',
                'description' => 'Annual technology conference featuring latest trends in AI, Machine Learning, and Cloud Computing. Join industry leaders and innovators for networking and learning opportunities.',
                'start_date' => now()->subDays(10),
                'end_date' => now()->subDays(3),
                'location' => 'Jakarta Convention Center, Hall A',
                'quota' => 100,
                'price' => 250000,
                'status' => 'completed',
                'image' => null,
            ]);
            $this->command->info('✓ Event created with ID: ' . $event->id);
            $this->command->info('  Event owner ID: ' . $event->user_id);
        } else {
            // Pastikan ownernya benar
            if ($event->user_id !== $organizer->id) {
                $event->update(['user_id' => $organizer->id]);
                $this->command->info('✓ Event owner updated to organizer ID: ' . $organizer->id);
            } else {
                $this->command->info('✓ Event already exists with correct owner');
            }
        }

        // Feedback comments dengan variasi rating
        $feedbackComments = [
            [
                'rating' => 5, 
                'comment' => 'Excellent event! The speakers were knowledgeable and the content was very relevant. Learned a lot about AI and machine learning. The networking sessions were fantastic and I made valuable connections with industry professionals.'
            ],
            [
                'rating' => 4, 
                'comment' => 'Great organization and networking opportunities. The venue was perfect and well-equipped with modern facilities. Would love more hands-on workshops and practical coding sessions next time. Overall highly satisfied!'
            ],
            [
                'rating' => 5, 
                'comment' => 'Outstanding conference! Every session was valuable and well-structured. The organizers did an amazing job with the schedule and logistics. Highly recommended for tech professionals looking to expand their knowledge and network.'
            ],
            [
                'rating' => 3, 
                'comment' => 'Good content overall but some sessions were too advanced for beginners. Would appreciate more beginner-friendly tracks or separate skill levels. Food was decent but could be more varied. Parking was quite challenging during peak hours.'
            ],
            [
                'rating' => 4, 
                'comment' => 'Very informative sessions with great industry speakers sharing real-world experiences. The networking breaks were well-timed and allowed for meaningful conversations. WiFi could be more stable though, especially during peak hours when everyone is connected.'
            ],
            [
                'rating' => 5, 
                'comment' => 'Best tech conference I\'ve attended this year! Speakers were industry leaders and the content was cutting-edge. Worth every penny and time invested. The hands-on labs were exceptional and provided practical skills I can use immediately at work.'
            ],
            [
                'rating' => 4, 
                'comment' => 'Enjoyed the practical examples and real-world case studies presented throughout the sessions. The Q&A sessions were insightful with detailed answers. Would like longer Q&A sessions and more interactive workshops in future events to maximize learning.'
            ],
            [
                'rating' => 5, 
                'comment' => 'Phenomenal event! Well worth the investment. Great mix of technical depth and practical insights that can be applied immediately. Already recommended it to my colleagues and team members. Looking forward to attending next year\'s conference!'
            ],
            [
                'rating' => 4,
                'comment' => 'Very professional event with excellent content delivery and engaging presentations. The venue was comfortable and accessible with good facilities. Minor issues with the initial registration process but staff was helpful and resolved quickly. Overall great experience!'
            ],
            [
                'rating' => 5,
                'comment' => 'Exceptional conference! The quality of speakers and topics exceeded my expectations. Networking opportunities were abundant and valuable with diverse attendees. The event app was user-friendly and helpful. Can\'t wait for the next edition of this amazing conference!'
            ],
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($feedbackComments as $index => $feedbackData) {
            $participantEmail = 'participant.feedback' . ($index + 1) . '@example.com';

            // Create or get participant
            $participant = User::where('email', $participantEmail)->first();

            if (!$participant) {
                $participant = User::create([
                    'name' => 'Feedback Participant ' . ($index + 1),
                    'full_name' => 'Feedback Participant ' . ($index + 1),
                    'email' => $participantEmail,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'participant', // Sesuai dengan DummyDataSeeder
                    'phone' => '+628123456780' . $index,
                    'bio' => 'Event participant for testing feedback summary feature',
                    'avatar' => null,
                ]);
            }

            // Register participant for event
            $existingParticipant = EventParticipant::where('user_id', $participant->id)
                ->where('event_id', $event->id)
                ->first();

            if (!$existingParticipant) {
                EventParticipant::create([
                    'event_id' => $event->id,
                    'user_id' => $participant->id,
                    'status' => 'attended',
                    'is_paid' => true,
                    'amount_paid' => $event->price,
                    'payment_reference' => 'PAY-TEST-' . time() . '-' . $index,
                    'attended_at' => $event->start_date, // Sesuai dengan DummyDataSeeder
                ]);
            }

            // Create feedback
            $existingFeedback = Feedback::where('user_id', $participant->id)
                ->where('event_id', $event->id)
                ->first();

            if (!$existingFeedback) {
                Feedback::create([
                    'user_id' => $participant->id,
                    'event_id' => $event->id,
                    'rating' => $feedbackData['rating'],
                    'comment' => $feedbackData['comment'],
                ]);
                $createdCount++;
            } else {
                $skippedCount++;
            }
        }

        // Summary Results
        $totalFeedbacks = Feedback::where('event_id', $event->id)->count();
        $avgRating = Feedback::where('event_id', $event->id)->avg('rating');
        $ratingDistribution = Feedback::where('event_id', $event->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating');

        // Display comprehensive summary
        $this->command->info('');
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->info('            SEEDER COMPLETED SUCCESSFULLY! ✓               ');
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->info('');

        $this->command->comment('👤 Organizer Details:');
        $this->command->info('  • ID: ' . $organizer->id);
        $this->command->info('  • Name: ' . $organizer->name);
        $this->command->info('  • Email: ' . $organizer->email);
        $this->command->info('  • Role: ' . $organizer->role);
        $this->command->info('');

        $this->command->comment('📊 Event Details:');
        $this->command->info('  • Event ID: ' . $event->id);
        $this->command->info('  • Title: ' . $event->title);
        $this->command->info('  • Owner ID: ' . $event->user_id);
        $this->command->info('  • Status: ' . $event->status);
        $this->command->info('  • Match Owner: ' . ($event->user_id === $organizer->id ? '✅ YES' : '❌ NO'));
        $this->command->info('');
        
        $this->command->comment('📝 Feedback Statistics:');
        $this->command->info('  • Total Feedbacks: ' . $totalFeedbacks);
        $this->command->info('  • New Created: ' . $createdCount);
        $this->command->info('  • Skipped (existing): ' . $skippedCount);
        $this->command->info('  • Average Rating: ' . round($avgRating, 2) . '/5.0 ⭐');
        $this->command->info('');
        $this->command->info('  Rating Distribution:');
        for ($i = 5; $i >= 1; $i--) {
            $count = $ratingDistribution[$i] ?? 0;
            $stars = str_repeat('⭐', $i);
            $bar = str_repeat('█', $count);
            $this->command->info("    {$stars} ({$i}): {$bar} {$count}");
        }
        $this->command->info('');

        $this->command->comment('🔑 Login Credentials:');
        $this->command->info('  • Email: organizer@example.com');
        $this->command->info('  • Password: password');
        $this->command->info('');

        $this->command->comment('🌐 API Endpoints to Test:');
        $this->command->info('  1. Login:');
        $this->command->info('     POST /api/login');
        $this->command->info('');
        $this->command->info('  2. Get My Events:');
        $this->command->info('     GET /api/events/my-events');
        $this->command->info('');
        $this->command->info('  3. Check Ownership:');
        $this->command->info('     GET /api/events/' . $event->id . '/feedback/check-owner');
        $this->command->info('');
        $this->command->info('  4. Generate Summary:');
        $this->command->info('     POST /api/events/' . $event->id . '/feedback/generate-summary');
        $this->command->info('');
        $this->command->info('  5. Get Summary:');
        $this->command->info('     GET /api/events/' . $event->id . '/feedback/summary');
        $this->command->info('');

        $this->command->comment('🌐 Test Web Interface:');
        $this->command->info('  • http://localhost:8000/test-feedback-summary');
        $this->command->info('');
        
        $this->command->comment('📋 Next Steps:');
        $this->command->info('  1. ⚙️  Set ANTHROPIC_API_KEY in .env file');
        $this->command->info('  2. 🔐 Login: POST /api/login');
        $this->command->info('  3. 📋 Verify: GET /api/events/my-events');
        $this->command->info('  4. ✅ Check: GET /api/events/' . $event->id . '/feedback/check-owner');
        $this->command->info('  5. 🤖 Generate: POST /api/events/' . $event->id . '/feedback/generate-summary');
        $this->command->info('');
        
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        
        if ($totalFeedbacks < 5) {
            $this->command->warn('⚠️  WARNING: Event has less than 5 feedbacks!');
            $this->command->warn('   AI summary requires at least 5 feedbacks to generate.');
            $this->command->warn('   Current count: ' . $totalFeedbacks);
        } else {
            $this->command->info('✅ Event has sufficient feedbacks (' . $totalFeedbacks . ') for AI summary!');
            $this->command->info('✅ Ready to generate AI-powered feedback summary!');
        }
        
        $this->command->info('');
    }
}