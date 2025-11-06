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

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif

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
                            <i class="fas fa-award text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Certificates</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['certificates'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- My Events List -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">My Events</h3>
                        
                        @if(isset($myEvents) && $myEvents->count() > 0)
                            <div class="space-y-4">
                                @foreach($myEvents as $participation)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $participation->event->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $participation->event->start_date->format('F j, Y') }} • {{ $participation->event->start_date->format('H:i') }}</p>
                                                <p class="text-sm text-gray-600">{{ $participation->event->location }}</p>
                                                
                                                <div class="mt-2 flex items-center gap-2">
                                                    <!-- Status Badge -->
                                                    @if($participation->registration_status === 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>Approved
                                                        </span>
                                                    @elseif($participation->registration_status === 'pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @endif

                                                    <!-- Attendance Badge -->
                                                    @if($participation->attendance_status === 'attended')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Attended
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col items-end gap-2 ml-4">
                                                <!-- View Event Button -->
                                                <a href="{{ route('events.show', $participation->event) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </a>

                                                <!-- Feedback & Certificate Actions -->
                                                @if($participation->attendance_status === 'attended')
                                                    @php
                                                        $feedback = $participation->event->feedbacks->where('user_id', Auth::id())->first();
                                                    @endphp

                                                    @if(!$feedback)
                                                        <!-- Show Feedback Button if not submitted -->
                                                        <a href="{{ route('feedback.create', $participation->event) }}" 
                                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                            <i class="fas fa-comment mr-1"></i>Give Feedback
                                                        </a>
                                                    @else
                                                        <!-- Show Certificate Download if feedback submitted -->
                                                        <div class="flex flex-col gap-1">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check mr-1"></i>Feedback Given
                                                            </span>
                                                            <a href="{{ route('feedback.certificate.download', $participation->event) }}" 
                                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700">
                                                                <i class="fas fa-download mr-1"></i>Certificate
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 mb-4">You haven't joined any events yet</p>
                                <a href="{{ route('events.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-search mr-2"></i>Browse Events
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
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
                            @if(isset($recentActivities) && count($recentActivities) > 0)
                                @foreach($recentActivities as $activity)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-{{ $activity['color'] }}-400 rounded-full mt-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">No recent activities</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>