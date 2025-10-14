<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Tech conferences, workshops, and meetups',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'description' => 'Business seminars, networking events, and conferences',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'description' => 'Educational workshops, training sessions, and courses',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Health seminars, fitness events, and wellness workshops',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Arts & Culture',
                'description' => 'Art exhibitions, cultural events, and creative workshops',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports events, tournaments, and fitness activities',
                'color' => '#06B6D4',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
