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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        $this->clearExistingData();

        // Seed data in order
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
            // Admin Users (Event Organizers)
            [
                'name' => 'Admin',
                'full_name' => 'System Administrator',
                'email' => 'admin@eventconnect.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_organizer' => true,
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
                'is_organizer' => true,
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
                'is_organizer' => true,
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
                'is_organizer' => true,
                'phone' => '+6281234567893',
                'bio' => 'Startup ecosystem builder and pitch competition organizer',
                'avatar' => null,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],

            // Participant Users
            [
                'name' => 'Alice Developer',
                'full_name' => 'Alice Wilson',
                'email' => 'alice@developer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567894',
                'bio' => 'Full-stack developer passionate about learning new technologies',
                'avatar' => null,
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(28),
            ],
            [
                'name' => 'Bob Designer',
                'full_name' => 'Robert Davis',
                'email' => 'bob@designer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567895',
                'bio' => 'UI/UX designer with focus on user experience',
                'avatar' => null,
                'created_at' => now()->subDays(26),
                'updated_at' => now()->subDays(26),
            ],
            [
                'name' => 'Carol Manager',
                'full_name' => 'Carol Miller',
                'email' => 'carol@manager.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567896',
                'bio' => 'Project manager with expertise in agile methodologies',
                'avatar' => null,
                'created_at' => now()->subDays(24),
                'updated_at' => now()->subDays(24),
            ],
            [
                'name' => 'David Student',
                'full_name' => 'David Garcia',
                'email' => 'david@student.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567897',
                'bio' => 'Computer science student eager to learn and network',
                'avatar' => null,
                'created_at' => now()->subDays(22),
                'updated_at' => now()->subDays(22),
            ],
            [
                'name' => 'Eva Entrepreneur',
                'full_name' => 'Eva Rodriguez',
                'email' => 'eva@entrepreneur.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567898',
                'bio' => 'Serial entrepreneur looking for networking opportunities',
                'avatar' => null,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name' => 'Frank Analyst',
                'full_name' => 'Frank Martinez',
                'email' => 'frank@analyst.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567899',
                'bio' => 'Data analyst with expertise in business intelligence',
                'avatar' => null,
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'name' => 'Grace Marketer',
                'full_name' => 'Grace Lee',
                'email' => 'grace@marketer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567800',
                'bio' => 'Digital marketing specialist with social media expertise',
                'avatar' => null,
                'created_at' => now()->subDays(16),
                'updated_at' => now()->subDays(16),
            ],
            [
                'name' => 'Henry Consultant',
                'full_name' => 'Henry Kim',
                'email' => 'henry@consultant.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567801',
                'bio' => 'Business consultant with focus on digital transformation',
                'avatar' => null,
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'name' => 'Ivy Researcher',
                'full_name' => 'Ivy Chen',
                'email' => 'ivy@researcher.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567802',
                'bio' => 'Research scientist in artificial intelligence and machine learning',
                'avatar' => null,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'name' => 'Jack Developer',
                'full_name' => 'Jack Wang',
                'email' => 'jack@developer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567803',
                'bio' => 'Mobile app developer specializing in React Native',
                'avatar' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name' => 'Kate Designer',
                'full_name' => 'Kate Liu',
                'email' => 'kate@designer.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567804',
                'bio' => 'Graphic designer with expertise in branding and visual identity',
                'avatar' => null,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'name' => 'Leo Manager',
                'full_name' => 'Leo Zhang',
                'email' => 'leo@manager.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567805',
                'bio' => 'Product manager with experience in SaaS platforms',
                'avatar' => null,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'name' => 'Maya Student',
                'full_name' => 'Maya Patel',
                'email' => 'maya@student.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567806',
                'bio' => 'Information technology student with interest in cybersecurity',
                'avatar' => null,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'Noah Entrepreneur',
                'full_name' => 'Noah Kumar',
                'email' => 'noah@entrepreneur.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567807',
                'bio' => 'Tech entrepreneur building the next unicorn startup',
                'avatar' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Olivia Analyst',
                'full_name' => 'Olivia Singh',
                'email' => 'olivia@analyst.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'is_organizer' => false,
                'phone' => '+6281234567808',
                'bio' => 'Financial analyst with expertise in fintech and blockchain',
                'avatar' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ];

        User::insert($users);
        $this->command->info('✅ ' . count($users) . ' users seeded');
    }

    private function seedCategories()
    {
        $this->command->info('📂 Seeding categories...');

        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Events related to technology, programming, and software development',
                'color' => '#3B82F6',
                'is_active' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name' => 'Business',
                'description' => 'Business conferences, networking events, and entrepreneurship',
                'color' => '#10B981',
                'is_active' => true,
                'created_at' => now()->subDays(29),
                'updated_at' => now()->subDays(29),
            ],
            [
                'name' => 'Education',
                'description' => 'Educational workshops, seminars, and learning events',
                'color' => '#F59E0B',
                'is_active' => true,
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(28),
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Health, fitness, and wellness related events',
                'color' => '#EF4444',
                'is_active' => true,
                'created_at' => now()->subDays(27),
                'updated_at' => now()->subDays(27),
            ],
            [
                'name' => 'Arts & Culture',
                'description' => 'Art exhibitions, cultural events, and creative workshops',
                'color' => '#8B5CF6',
                'is_active' => true,
                'created_at' => now()->subDays(26),
                'updated_at' => now()->subDays(26),
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports events, tournaments, and fitness activities',
                'color' => '#06B6D4',
                'is_active' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'name' => 'Food & Drink',
                'description' => 'Culinary events, food festivals, and cooking workshops',
                'color' => '#84CC16',
                'is_active' => true,
                'created_at' => now()->subDays(24),
                'updated_at' => now()->subDays(24),
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Entertainment events, concerts, and shows',
                'color' => '#F97316',
                'is_active' => true,
                'created_at' => now()->subDays(23),
                'updated_at' => now()->subDays(23),
            ],
            [
                'name' => 'Science',
                'description' => 'Scientific conferences, research presentations, and STEM events',
                'color' => '#EC4899',
                'is_active' => true,
                'created_at' => now()->subDays(22),
                'updated_at' => now()->subDays(22),
            ],
            [
                'name' => 'Environment',
                'description' => 'Environmental awareness, sustainability, and green technology events',
                'color' => '#6B7280',
                'is_active' => true,
                'created_at' => now()->subDays(21),
                'updated_at' => now()->subDays(21),
            ],
        ];

        Category::insert($categories);
        $this->command->info('✅ ' . count($categories) . ' categories seeded');
    }

    private function seedEvents()
    {
        $this->command->info('🎉 Seeding events...');

        $organizers = User::where('role', 'admin')->get();
        $categories = Category::all();

        $events = [
            // Tech Events
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Annual technology conference featuring the latest trends in AI, blockchain, and cloud computing. Join industry leaders and innovators for a day of learning and networking.',
                'location' => 'Jakarta Convention Center',
                'start_date' => now()->addDays(15)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(15)->addHours(8)->format('Y-m-d H:i:s'),
                'quota' => 500,
                'price' => 250000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'title' => 'Laravel Workshop Advanced',
                'description' => 'Deep dive into advanced Laravel concepts including custom packages, testing strategies, and performance optimization.',
                'location' => 'Bandung Tech Hub',
                'start_date' => now()->addDays(8)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(8)->addHours(6)->format('Y-m-d H:i:s'),
                'quota' => 50,
                'price' => 150000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'title' => 'React Native Bootcamp',
                'description' => 'Complete React Native development bootcamp from basics to advanced mobile app development.',
                'location' => 'Surabaya Digital Hub',
                'start_date' => now()->addDays(25)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(27)->format('Y-m-d H:i:s'),
                'quota' => 30,
                'price' => 500000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDays(16),
                'updated_at' => now()->subDays(16),
            ],

            // Business Events
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Annual startup pitch competition with prizes up to 100 million IDR. Perfect for entrepreneurs looking to showcase their ideas.',
                'location' => 'Yogyakarta Innovation Center',
                'start_date' => now()->addDays(12)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(12)->addHours(6)->format('Y-m-d H:i:s'),
                'quota' => 200,
                'price' => 100000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Business')->first()->id,
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'title' => 'Digital Marketing Masterclass',
                'description' => 'Learn advanced digital marketing strategies including SEO, SEM, social media marketing, and content marketing.',
                'location' => 'Jakarta Marketing Hub',
                'start_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(20)->addHours(4)->format('Y-m-d H:i:s'),
                'quota' => 100,
                'price' => 200000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Business')->first()->id,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],

            // Education Events
            [
                'title' => 'Data Science Workshop',
                'description' => 'Introduction to data science with Python, machine learning basics, and data visualization techniques.',
                'location' => 'Bandung University',
                'start_date' => now()->addDays(18)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(18)->addHours(5)->format('Y-m-d H:i:s'),
                'quota' => 40,
                'price' => 180000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Education')->first()->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'UI/UX Design Bootcamp',
                'description' => 'Comprehensive UI/UX design bootcamp covering user research, wireframing, prototyping, and design systems.',
                'location' => 'Surabaya Design School',
                'start_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(32)->format('Y-m-d H:i:s'),
                'quota' => 25,
                'price' => 400000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Education')->first()->id,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],

            // Health & Wellness Events
            [
                'title' => 'Yoga & Meditation Retreat',
                'description' => 'Weekend yoga and meditation retreat for stress relief and mental wellness.',
                'location' => 'Yogyakarta Wellness Center',
                'start_date' => now()->addDays(22)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(23)->format('Y-m-d H:i:s'),
                'quota' => 20,
                'price' => 300000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Health & Wellness')->first()->id,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],

            // Arts & Culture Events
            [
                'title' => 'Digital Art Exhibition',
                'description' => 'Contemporary digital art exhibition featuring works from local and international artists.',
                'location' => 'Jakarta Art Gallery',
                'start_date' => now()->addDays(28)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
                'quota' => 150,
                'price' => 50000,
                'status' => 'published',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Arts & Culture')->first()->id,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],

            // Past Events
            [
                'title' => 'Web Development Bootcamp',
                'description' => 'Complete web development bootcamp covering HTML, CSS, JavaScript, and modern frameworks.',
                'location' => 'Bandung Coding Academy',
                'start_date' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'end_date' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'quota' => 35,
                'price' => 350000,
                'status' => 'completed',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Business Networking Event',
                'description' => 'Monthly business networking event for entrepreneurs and professionals.',
                'location' => 'Jakarta Business Center',
                'start_date' => now()->subDays(10)->format('Y-m-d H:i:s'),
                'end_date' => now()->subDays(10)->addHours(3)->format('Y-m-d H:i:s'),
                'quota' => 80,
                'price' => 75000,
                'status' => 'completed',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Business')->first()->id,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(10),
            ],

            // Draft Events
            [
                'title' => 'AI & Machine Learning Summit',
                'description' => 'Comprehensive summit on artificial intelligence and machine learning applications in various industries.',
                'location' => 'Jakarta AI Center',
                'start_date' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(45)->addHours(8)->format('Y-m-d H:i:s'),
                'quota' => 300,
                'price' => 400000,
                'status' => 'draft',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Blockchain Technology Workshop',
                'description' => 'Hands-on workshop on blockchain technology, smart contracts, and cryptocurrency development.',
                'location' => 'Surabaya Blockchain Hub',
                'start_date' => now()->addDays(35)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(35)->addHours(6)->format('Y-m-d H:i:s'),
                'quota' => 60,
                'price' => 280000,
                'status' => 'draft',
                'image' => null,
                'user_id' => $organizers->random()->id,
                'category_id' => $categories->where('name', 'Technology')->first()->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ];

        Event::insert($events);
        $this->command->info('✅ ' . count($events) . ' events seeded');
    }

    private function seedEventParticipants()
    {
        $this->command->info('👥 Seeding event participants...');

        $events = Event::where('status', 'published')->get();
        $participants = User::where('role', 'participant')->get();
        $participantData = [];

        foreach ($events as $event) {
            // Random number of participants (30-80% of max capacity)
            $maxParticipants = min($event->quota, $participants->count());
            $participantCount = rand(
                (int)($maxParticipants * 0.3),
                (int)($maxParticipants * 0.8)
            );

            $selectedParticipants = $participants->random($participantCount);

            foreach ($selectedParticipants as $participant) {
                $statuses = ['registered', 'attended', 'cancelled'];
                $weights = [70, 25, 5]; // 70% registered, 25% attended, 5% cancelled
                $status = $this->weightedRandom($statuses, $weights);

                $participantData[] = [
                    'event_id' => $event->id,
                    'user_id' => $participant->id,
                    'status' => $status,
                    'is_paid' => $status === 'attended' ? true : (rand(1, 100) <= 80),
                    'amount_paid' => $status === 'attended' ? $event->price : null,
                    'payment_reference' => $status === 'attended' ? 'PAY-' . rand(100000, 999999) : null,
                    'attended_at' => $status === 'attended' ? $event->start_date : null,
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
            ->with(['event', 'user'])
            ->get();

        $feedbackData = [];

        foreach ($attendedParticipants as $participant) {
            // 80% chance to leave feedback
            if (rand(1, 100) <= 80) {
                $ratings = [1, 2, 3, 4, 5];
                $weights = [5, 10, 15, 35, 35]; // More likely to be 4-5 stars
                $rating = $this->weightedRandom($ratings, $weights);

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

                $feedbackData[] = [
                    'event_id' => $participant->event_id,
                    'user_id' => $participant->user_id,
                    'rating' => $rating,
                    'comment' => $feedbackTemplates[array_rand($feedbackTemplates)],
                    'created_at' => $participant->attendance_date ? 
                        Carbon::parse($participant->attendance_date)->addHours(rand(1, 24)) : 
                        now()->subDays(rand(1, 10)),
                    'updated_at' => now()->subDays(rand(1, 10)),
                ];
            }
        }

        Feedback::insert($feedbackData);
        $this->command->info('✅ ' . count($feedbackData) . ' feedbacks seeded');
    }

    private function seedNotifications()
    {
        $this->command->info('🔔 Seeding notifications...');

        $users = User::all();
        $events = Event::where('status', 'published')->get();

        $notificationData = [];

        foreach ($users as $user) {
            // Random number of notifications per user (5-15)
            $notificationCount = rand(5, 15);

            for ($i = 0; $i < $notificationCount; $i++) {
                $types = ['event_reminder', 'event_cancelled', 'event_updated', 'new_event', 'feedback_request'];
                $type = $types[array_rand($types)];

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

                $notificationData[] = [
                    'user_id' => $user->id,
                    'event_id' => $events->random()->id,
                    'type' => $type,
                    'title' => $titles[$type],
                    'message' => $messages[$type],
                    'is_read' => rand(1, 100) <= 70,
                    'data' => json_encode(['event_id' => $events->random()->id]),
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