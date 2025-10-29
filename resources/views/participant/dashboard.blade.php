<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Dashboard - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('events.index') }}" class="flex-shrink-0 flex items-center hover:opacity-80 transition-opacity">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">Event Connect</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>Browse Events
                    </a>
                    <span class="text-gray-700">Welcome, {{ Auth::user()->full_name }}</span>
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Participant Dashboard</h1>
            <p class="mt-2 text-gray-600">Manage your event participations and activities</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Events Joined</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_registered'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-certificate text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Events Attended</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['attended_events'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Upcoming Events</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['upcoming_events'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bell text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Role</dt>
                                <dd class="text-lg font-medium text-gray-900">Participant</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upcoming Events -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Upcoming Events</h3>
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">Tech Conference 2025</h4>
                                        <p class="text-sm text-gray-500">December 15, 2025 • 09:00 - 17:00</p>
                                        <p class="text-sm text-gray-600">Convention Center, Jakarta</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Joined
                                        </span>
                                        <a href="{{ route('events.show', 1) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">Marketing Workshop</h4>
                                        <p class="text-sm text-gray-500">December 20, 2025 • 10:00 - 16:00</p>
                                        <p class="text-sm text-gray-600">Online Event</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                        <a href="{{ route('events.show', 2) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-6">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('events.index') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>Browse Events
                            </a>
                            <a href="{{ route('attendance.scanner') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-qrcode mr-2"></i>Scan QR Code
                            </a>
                            <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-user mr-2"></i>Edit Profile
                            </a>
                            <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-certificate mr-2"></i>My Certificates
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mt-2"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Joined "Design Thinking Workshop"</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Downloaded certificate for "Tech Meetup"</p>
                                    <p class="text-xs text-gray-500">1 day ago</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Submitted feedback for "Marketing Seminar"</p>
                                    <p class="text-xs text-gray-500">3 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
