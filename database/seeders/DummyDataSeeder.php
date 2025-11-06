<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Feedback;
use App\Models\Notification;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->clearExistingData();
        $this->seedUsers();
        $this->seedCategories();
        $this->seedEvents();
        $this->seedEventParticipants();
        $this->seedFeedbacks();
        $this->seedNotifications();

        $this->command->info('✅ Dummy data seeded successfully!');
    }

    private function clearExistingData()
    {
        $this->command->info('🧹 Clearing existing data...');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Notification::truncate();
        Feedback::truncate();
        EventParticipant::truncate();
        Event::truncate();
        Category::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedUsers()
    {
        $this->command->info('👥 Seeding users...');

        $users = [
            // Admin Users
            [
                'name' => 'Admin',
                'full_name' => 'System Administrator',
                'email' => 'admin@eventconnect.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+6281234567890',
                'bio' => 'System administrator for Event Connect platform',
                'avatar' => null,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name' => 'John Organizer',
                'full_name' => 'John Smith',
                'email' => 'john@techconf.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+6281234567891',
                'bio' => 'Tech conference organizer with 5+ years experience',
                'avatar' => null,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'name' => 'Sarah Events',
                'full_name' => 'Sarah Johnson',
                'email' => 'sarah@workshop.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+6281234567892',
                'bio' => 'Workshop specialist and event management expert',
                'avatar' => null,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name' => 'Mike Startup',
                'full_name' => 'Michael Brown',
                'email' => 'mike@startup.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+6281234567893',
                'bio' => 'Startup ecosystem builder and pitch competition organizer',
                'avatar' => null,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
        ];

        // Participant Users
        $participantNames = [
            ['Alice Developer', 'Alice Wilson', 'alice@developer.com', 'Full-stack developer passionate about learning new technologies', 28],
            ['Bob Designer', 'Robert Davis', 'bob@designer.com', 'UI/UX designer with focus on user experience', 26],
            ['Carol Manager', 'Carol Miller', 'carol@manager.com', 'Project manager with expertise in agile methodologies', 24],
            ['David Student', 'David Garcia', 'david@student.com', 'Computer science student eager to learn and network', 22],
            ['Eva Entrepreneur', 'Eva Rodriguez', 'eva@entrepreneur.com', 'Serial entrepreneur looking for networking opportunities', 20],
            ['Frank Analyst', 'Frank Martinez', 'frank@analyst.com', 'Data analyst with expertise in business intelligence', 18],
            ['Grace Marketer', 'Grace Lee', 'grace@marketer.com', 'Digital marketing specialist with social media expertise', 16],
            ['Henry Consultant', 'Henry Kim', 'henry@consultant.com', 'Business consultant with focus on digital transformation', 14],
            ['Ivy Researcher', 'Ivy Chen', 'ivy@researcher.com', 'Research scientist in artificial intelligence and machine learning', 12],
            ['Jack Developer', 'Jack Wang', 'jack@developer.com', 'Mobile app developer specializing in React Native', 10],
            ['Kate Designer', 'Kate Liu', 'kate@designer.com', 'Graphic designer with expertise in branding and visual identity', 8],
            ['Leo Manager', 'Leo Zhang', 'leo@manager.com', 'Product manager with experience in SaaS platforms', 6],
            ['Maya Student', 'Maya Patel', 'maya@student.com', 'Information technology student with interest in cybersecurity', 4],
            ['Noah Entrepreneur', 'Noah Kumar', 'noah@entrepreneur.com', 'Tech entrepreneur building the next unicorn startup', 2],
            ['Olivia Analyst', 'Olivia Singh', 'olivia@analyst.com', 'Financial analyst with expertise in fintech and blockchain', 1],
        ];

        $phoneCounter = 894;
        foreach ($participantNames as $participant) {
            $users[] = [
                'name' => $participant[0],
                'full_name' => $participant[1],
                'email' => $participant[2],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'phone' => '+62812345678' . str_pad($phoneCounter++, 2, '0', STR_PAD_LEFT),
                'bio' => $participant[3],
                'avatar' => null,
                'created_at' => now()->subDays($participant[4]),
                'updated_at' => now()->subDays($participant[4]),
            ];
        }

        User::insert($users);
        $this->command->info('✅ ' . count($users) . ' users seeded');
    }

    private function seedCategories()
    {
        $this->command->info('📂 Seeding categories...');

        $categories = [
            ['Technology', 'Events related to technology, programming, and software development', '#3B82F6', 30],
            ['Business', 'Business conferences, networking events, and entrepreneurship', '#10B981', 29],
            ['Education', 'Educational workshops, seminars, and learning events', '#F59E0B', 28],
            ['Health & Wellness', 'Health, fitness, and wellness related events', '#EF4444', 27],
            ['Arts & Culture', 'Art exhibitions, cultural events, and creative workshops', '#8B5CF6', 26],
            ['Sports', 'Sports events, tournaments, and fitness activities', '#06B6D4', 25],
            ['Food & Drink', 'Culinary events, food festivals, and cooking workshops', '#84CC16', 24],
            ['Entertainment', 'Entertainment events, concerts, and shows', '#F97316', 23],
            ['Science', 'Scientific conferences, research presentations, and STEM events', '#EC4899', 22],
            ['Environment', 'Environmental awareness, sustainability, and green technology events', '#6B7280', 21],
        ];

        $categoryData = [];
        foreach ($categories as $cat) {
            $categoryData[] = [
                'name' => $cat[0],
                'description' => $cat[1],
                'color' => $cat[2],
                'is_active' => true,
                'created_at' => now()->subDays($cat[3]),
                'updated_at' => now()->subDays($cat[3]),
            ];
        }

        Category::insert($categoryData);
        $this->command->info('✅ ' . count($categoryData) . ' categories seeded');
    }

    private function seedEvents()
    {
        $this->command->info('🎉 Seeding events...');

        $organizers = User::where('role', 'admin')->get();
        $categories = Category::all();

        $events = [
            ['Test Event Today', 'Event untuk ngetes feedback hari ini.', 'Jakarta Tech Center', 0, 4, 30, 100000, 'published', 'Technology', 1],
            ['Tech Conference 2024', 'Annual technology conference featuring the latest trends in AI, blockchain, and cloud computing.', 'Jakarta Convention Center', 15, 8, 500, 250000, 'published', 'Technology', 20],
            ['Laravel Workshop Advanced', 'Deep dive into advanced Laravel concepts including custom packages, testing strategies, and performance optimization.', 'Bandung Tech Hub', 8, 6, 50, 150000, 'published', 'Technology', 18],
            ['React Native Bootcamp', 'Complete React Native development bootcamp from basics to advanced mobile app development.', 'Surabaya Digital Hub', 25, 48, 30, 500000, 'published', 'Technology', 16],
            ['Startup Pitch Competition', 'Annual startup pitch competition with prizes up to 100 million IDR.', 'Yogyakarta Innovation Center', 12, 6, 200, 100000, 'published', 'Business', 14],
            ['Digital Marketing Masterclass', 'Learn advanced digital marketing strategies including SEO, SEM, social media marketing.', 'Jakarta Marketing Hub', 20, 4, 100, 200000, 'published', 'Business', 12],
            ['Data Science Workshop', 'Introduction to data science with Python, machine learning basics, and data visualization.', 'Bandung University', 18, 5, 40, 180000, 'published', 'Education', 10],
            ['UI/UX Design Bootcamp', 'Comprehensive UI/UX design bootcamp covering user research, wireframing, prototyping.', 'Surabaya Design School', 30, 48, 25, 400000, 'published', 'Education', 8],
            ['Yoga & Meditation Retreat', 'Weekend yoga and meditation retreat for stress relief and mental wellness.', 'Yogyakarta Wellness Center', 22, 24, 20, 300000, 'published', 'Health & Wellness', 6],
            ['Digital Art Exhibition', 'Contemporary digital art exhibition featuring works from local and international artists.', 'Jakarta Art Gallery', 28, 48, 150, 50000, 'published', 'Arts & Culture', 4],
            ['Web Development Bootcamp', 'Complete web development bootcamp covering HTML, CSS, JavaScript, and modern frameworks.', 'Bandung Coding Academy', -5, 48, 35, 350000, 'completed', 'Technology', 20],
            ['Business Networking Event', 'Monthly business networking event for entrepreneurs and professionals.', 'Jakarta Business Center', -10, 3, 80, 75000, 'completed', 'Business', 25],
            ['AI & Machine Learning Summit', 'Comprehensive summit on artificial intelligence and machine learning applications.', 'Jakarta AI Center', 45, 8, 300, 400000, 'draft', 'Technology', 2],
            ['Blockchain Technology Workshop', 'Hands-on workshop on blockchain technology, smart contracts, and cryptocurrency.', 'Surabaya Blockchain Hub', 35, 6, 60, 280000, 'draft', 'Technology', 1],
        ];

        $eventData = [];
        foreach ($events as $event) {
            $category = $categories->where('name', $event[8])->first();
            
            $eventData[] = [
                'title' => $event[0],
                'description' => $event[1],
                'location' => $event[2],
                'start_date' => now()->addDays($event[3])->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays($event[3])->addHours($event[4])->format('Y-m-d H:i:s'),
                'quota' => $event[5],
                'price' => $event[6],
                'status' => $event[7],
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $category->id,
                'created_at' => now()->subDays($event[9]),
                'updated_at' => now()->subDays($event[9]),
            ];
        }
        

        Event::insert($eventData);
        $this->command->info('✅ ' . count($eventData) . ' events seeded');
    }

    private function seedEventParticipants()
    {
        $this->command->info('👥 Seeding event participants...');

        $events = Event::whereIn('status', ['published', 'completed'])->get();
        $participants = User::where('role', 'participant')->get();
        $participantData = [];

        foreach ($events as $event) {
            $maxParticipants = min($event->quota, $participants->count());
            $participantCount = rand((int)($maxParticipants * 0.3), (int)($maxParticipants * 0.8));

            // Shuffle dan ambil unique participants
            $selectedParticipants = $participants->shuffle()->take($participantCount);

            foreach ($selectedParticipants as $participant) {
                $statuses = ['registered', 'attended', 'cancelled'];
                $weights = [70, 25, 5];
                $status = $this->weightedRandom($statuses, $weights);

                $attendedAt = null;
                if ($status === 'attended' && $event->status === 'completed') {
                    $attendedAt = $event->start_date;
                }

                $participantData[] = [
                    'event_id' => $event->id,
                    'user_id' => $participant->id,
                    'status' => $status,
                    'is_paid' => $status === 'attended' ? true : (rand(1, 100) <= 80),
                    'amount_paid' => $status === 'attended' ? $event->price : null,
                    'payment_reference' => $status === 'attended' ? 'PAY-' . rand(100000, 999999) : null,
                    'attended_at' => $attendedAt,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ];
            }
        }

        EventParticipant::insert($participantData);
        $this->command->info('✅ ' . count($participantData) . ' event participants seeded');
    }

    private function seedFeedbacks()
    {
        $this->command->info('💬 Seeding feedbacks...');

        $attendedParticipants = EventParticipant::where('status', 'attended')
            ->whereNotNull('attended_at')
            ->get();

        $feedbackData = [];
        $feedbackTemplates = [
            'Great event! Very informative and well-organized.',
            'Excellent speakers and valuable content.',
            'Good event, learned a lot from the presentations.',
            'Well-structured program with practical insights.',
            'Amazing networking opportunities and great venue.',
            'Could be better organized, but overall good experience.',
            'Outstanding event! Highly recommend for next year.',
            'Good content but could improve on time management.',
            'Fantastic speakers and engaging discussions.',
            'Very professional event with high-quality content.',
        ];

        foreach ($attendedParticipants as $participant) {
            if (rand(1, 100) <= 80) {
                $ratings = [1, 2, 3, 4, 5];
                $weights = [5, 10, 15, 35, 35];
                $rating = $this->weightedRandom($ratings, $weights);

                $feedbackData[] = [
                    'event_id' => $participant->event_id,
                    'user_id' => $participant->user_id,
                    'rating' => $rating,
                    'comment' => $feedbackTemplates[array_rand($feedbackTemplates)],
                    'created_at' => Carbon::parse($participant->attended_at)->addHours(rand(1, 24)),
                    'updated_at' => Carbon::parse($participant->attended_at)->addHours(rand(1, 24)),
                ];
            }
        }

        if (!empty($feedbackData)) {
            Feedback::insert($feedbackData);
        }
        
        $this->command->info('✅ ' . count($feedbackData) . ' feedbacks seeded');
    }

    private function seedNotifications()
    {
        $this->command->info('🔔 Seeding notifications...');

        $users = User::all();
        $events = Event::where('status', 'published')->get();

        if ($events->isEmpty()) {
            $this->command->warn('⚠️ No published events found, skipping notifications');
            return;
        }

        $notificationData = [];
        $types = ['event_reminder', 'event_cancelled', 'event_updated', 'new_event', 'feedback_request'];
        
        $titles = [
            'event_reminder' => 'Event Reminder',
            'event_cancelled' => 'Event Cancelled',
            'event_updated' => 'Event Updated',
            'new_event' => 'New Event Available',
            'feedback_request' => 'Please Leave Feedback',
        ];

        $messages = [
            'event_reminder' => 'Don\'t forget! Your event is starting soon.',
            'event_cancelled' => 'Unfortunately, the event has been cancelled.',
            'event_updated' => 'The event details have been updated.',
            'new_event' => 'A new event matching your interests is now available.',
            'feedback_request' => 'Please share your feedback about the recent event.',
        ];

        foreach ($users as $user) {
            $notificationCount = rand(5, 15);

            for ($i = 0; $i < $notificationCount; $i++) {
                $type = $types[array_rand($types)];
                $event = $events->random();

                $notificationData[] = [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'type' => $type,
                    'title' => $titles[$type],
                    'message' => $messages[$type],
                    'is_read' => rand(1, 100) <= 70,
                    'data' => json_encode(['event_id' => $event->id]),
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ];
            }
        }

        Notification::insert($notificationData);
        $this->command->info('✅ ' . count($notificationData) . ' notifications seeded');
    }

    private function weightedRandom($items, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $item;
            }
        }

        return $items[0];
    }
}