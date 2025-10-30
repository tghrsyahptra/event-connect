<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800">Event Connect</h1>
            <p class="text-gray-600 text-sm">Admin Dashboard</p>
        </div>
        <nav class="mt-6">
            <a href="/admin/dashboard" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
            <a href="/admin/users" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50"><i class="fas fa-users mr-3"></i>Users</a>
            <a href="/admin/events" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
            <a href="/admin/finance" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-4 border-blue-500"><i class="fas fa-coins mr-3"></i>Finance</a>
            <a href="/admin/categories" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50"><i class="fas fa-tags mr-3"></i>Categories</a>
            <a href="/admin/analytics" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50"><i class="fas fa-chart-line mr-3"></i>Analytics</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-6 px-6">
                @csrf
                <button type="submit" class="w-full flex items-center justify-start px-0 py-3 text-left text-red-600 hover:bg-red-50 rounded">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </button>
            </form>
        </nav>
    </div>
    <div class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm border-b">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Financial Management</h2>
                    <p class="text-gray-600">Overview of your events revenue and payments.</p>
                </div>
            </div>
        </header>

        <div class="p-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 shadow">
                    <div class="text-gray-500 text-sm">Total Events</div>
                    <div class="text-2xl font-bold">{{ number_format($summary['total_events']) }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <div class="text-gray-500 text-sm">Paid Registrations</div>
                    <div class="text-2xl font-bold">{{ number_format($summary['total_paid_registrations']) }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <div class="text-gray-500 text-sm">Total Revenue</div>
                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <div class="text-gray-500 text-sm">Pending Payments</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($summary['pending_payments']) }}</div>
                </div>
            </div>

            <!-- Events Revenue Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Registrants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $event->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($event->price ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->paid_participants_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($event->total_revenue ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.events.finance', $event->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-chart-line mr-1"></i>Details</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


