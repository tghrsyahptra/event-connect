<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Event Connect Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">Event Connect</h1>
                <p class="text-gray-600 text-sm">Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="/admin/dashboard" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="/admin/users" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="/admin/events" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events
                </a>
                <a href="/admin/categories" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-tags mr-3"></i>
                    Categories
                </a>
                <a href="/admin/analytics" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-4 border-blue-500">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Analytics
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-gray-800">Analytics & Reports</h2>
                    <p class="text-gray-600">Detailed insights and analytics for your event platform.</p>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <!-- Date Range Selector -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700">Date Range:</label>
                            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                                <option>Last year</option>
                                <option>Custom range</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i>Export Report
                            </button>
                            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-refresh mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900">Rp 12,450,000</p>
                                <p class="text-sm text-green-600">+12.5% from last month</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-dollar-sign text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Avg. Event Rating</p>
                                <p class="text-2xl font-bold text-gray-900">4.7/5</p>
                                <p class="text-sm text-green-600">+0.3 from last month</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                                <p class="text-2xl font-bold text-gray-900">68.2%</p>
                                <p class="text-sm text-green-600">+5.2% from last month</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-percentage text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Users</p>
                                <p class="text-2xl font-bold text-gray-900">1,234</p>
                                <p class="text-sm text-green-600">+8.1% from last month</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Monthly Events Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Events Created Over Time</h3>
                        <canvas id="monthlyEventsChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Category Distribution -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Events by Category</h3>
                        <canvas id="categoryChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- User Registration Trends -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Registration Trends</h3>
                        <canvas id="userTrendsChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Revenue Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Over Time</h3>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Detailed Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Performing Events -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Events</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Tech Conference 2025</p>
                                        <p class="text-sm text-gray-500">150 participants</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp 2,250,000</p>
                                    <p class="text-sm text-gray-500">Revenue</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Marketing Workshop</p>
                                        <p class="text-sm text-gray-500">98 participants</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp 1,470,000</p>
                                    <p class="text-sm text-gray-500">Revenue</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Design Thinking Session</p>
                                        <p class="text-sm text-gray-500">75 participants</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp 1,125,000</p>
                                    <p class="text-sm text-gray-500">Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Activity Summary -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity Summary</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700">New Registrations</span>
                                </div>
                                <span class="font-bold text-gray-900">45</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700">Event Joins</span>
                                </div>
                                <span class="font-bold text-gray-900">128</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700">Feedback Submitted</span>
                                </div>
                                <span class="font-bold text-gray-900">89</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700">Certificates Downloaded</span>
                                </div>
                                <span class="font-bold text-gray-900">67</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700">Event Cancellations</span>
                                </div>
                                <span class="font-bold text-gray-900">12</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Monthly Events Chart
        const monthlyCtx = document.getElementById('monthlyEventsChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json(collect($monthlyTrends)->pluck('month')),
                datasets: [{
                    label: 'Events Created',
                    data: @json(collect($monthlyTrends)->pluck('events')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryAnalytics->pluck('name')),
                datasets: [{
                    data: @json($categoryAnalytics->pluck('events_count')),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                    ]
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

        // User Trends Chart
        const userTrendsCtx = document.getElementById('userTrendsChart').getContext('2d');
        new Chart(userTrendsCtx, {
            type: 'bar',
            data: {
                labels: @json(collect($userAnalytics['user_trends'])->pluck('date')),
                datasets: [{
                    label: 'New Users',
                    data: @json(collect($userAnalytics['user_trends'])->pluck('count')),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'New Events',
                    data: @json(collect($eventAnalytics['event_trends'])->pluck('count')),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
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

        // Revenue Chart (Sample data)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: [1200000, 1900000, 3000000, 5000000, 2000000, 3000000],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
