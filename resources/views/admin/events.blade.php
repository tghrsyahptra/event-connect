<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management - Event Connect Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="/admin/events" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-4 border-blue-500">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events
                </a>
                <a href="/admin/categories" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-tags mr-3"></i>
                    Categories
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-gray-800">Events Management</h2>
                    <p class="text-gray-600">Manage all events and their details.</p>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <!-- Search and Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" placeholder="Search events..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>All Events</option>
                                <option>Active Events</option>
                                <option>Completed Events</option>
                                <option>Paid Events</option>
                                <option>Free Events</option>
                            </select>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($events as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($event->image)
                                                <img class="h-12 w-12 rounded-lg object-cover" src="{{ $event->image }}" alt="">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-calendar text-gray-600"></i>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                                                <div class="text-xs text-gray-400">{{ $event->location }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $event->user->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $event->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $event->category->color ?? '#3B82F6' }}"></div>
                                            <span class="text-sm text-gray-900">{{ $event->category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $event->participants_count }}/{{ $event->quota ?? '∞' }}</div>
                                        @if($event->quota)
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($event->participants_count / $event->quota) * 100) }}%"></div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $now = now();
                                            $startDate = \Carbon\Carbon::parse($event->start_date);
                                            $endDate = \Carbon\Carbon::parse($event->end_date);
                                        @endphp
                                        
                                        @if($now < $startDate)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Upcoming
                                            </span>
                                        @elseif($now >= $startDate && $now <= $endDate)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-play mr-1"></i>Live
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-check mr-1"></i>Completed
                                            </span>
                                        @endif
                                        
                                        @if($event->is_paid)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-dollar-sign mr-1"></i>Paid - Rp {{ number_format($event->price) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-gift mr-1"></i>Free
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900" title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-purple-600 hover:text-purple-900" title="View Participants">
                                                <i class="fas fa-users"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900" title="Delete Event">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
