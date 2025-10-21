@extends('admin.layout')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics Dashboard')
@section('page-description', 'Comprehensive analytics and insights for your events')

@section('content')
<!-- Analytics Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Events</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $monthlyData['total_events'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Participants</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $monthlyData['total_participants'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Active Events</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $monthlyData['active_events'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-percentage text-white"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Avg. Attendance</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $monthlyData['avg_attendance'] ?? 0 }}%</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Events Over Time Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Events Over Time</h3>
        <canvas id="eventsChart" width="400" height="200"></canvas>
    </div>
    
    <!-- Category Distribution Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Events by Category</h3>
        <canvas id="categoryChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- User Growth Chart -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">User Growth</h3>
    <canvas id="userGrowthChart" width="800" height="300"></canvas>
</div>

<!-- Detailed Statistics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Events -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top Events by Participants</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($monthlyData['top_events'] ?? [] as $event)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $event['title'] ?? 'Unknown Event' }}</p>
                            <p class="text-sm text-gray-500">{{ $event['category'] ?? 'Uncategorized' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $event['participants'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">participants</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No events data available</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Category Statistics -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Category Statistics</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($categoryStats as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category['color'] ?? '#3B82F6' }}"></div>
                        <span class="text-sm font-medium text-gray-900">{{ $category['name'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $category['percentage'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $category['count'] ?? 0 }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No category data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Export and Actions -->
<div class="mt-8 flex justify-between items-center">
    <div class="flex space-x-4">
        <a href="{{ route('admin.analytics.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            <i class="fas fa-download mr-2"></i>Export Data
        </a>
        <a href="{{ route('admin.analytics.realtime') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            <i class="fas fa-sync mr-2"></i>Real-time View
        </a>
    </div>
    
    <div class="text-sm text-gray-500">
        Last updated: {{ now()->format('M d, Y H:i') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
// Events Over Time Chart
const eventsCtx = document.getElementById('eventsChart').getContext('2d');
new Chart(eventsCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyData['months'] ?? []),
        datasets: [{
            label: 'Events Created',
            data: @json($monthlyData['events_data'] ?? []),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Category Distribution Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json(collect($categoryStats)->pluck('name')->toArray()),
        datasets: [{
            data: @json(collect($categoryStats)->pluck('count')->toArray()),
            backgroundColor: @json(collect($categoryStats)->pluck('color')->toArray())
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'bar',
    data: {
        labels: @json($userTrends['months'] ?? []),
        datasets: [{
            label: 'New Users',
            data: @json($userTrends['users_data'] ?? []),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection