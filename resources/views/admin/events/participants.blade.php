<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - {{ $event->title }} - Event Connect</title>
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
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Participants - {{ $event->title }}</h2>
                        <p class="text-gray-600">Manage event participants and attendance</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('attendance.qr-code', $event->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                            <i class="fas fa-qrcode mr-2"></i>View QR Code
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Events
                        </a>
                    </div>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Registered</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $event->registered_count }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Attended</p>
                            <p class="text-3xl font-bold text-green-600">{{ $participants->where('status', 'attended')->count() }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Registered</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $participants->where('status', 'registered')->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Cancelled</p>
                            <p class="text-3xl font-bold text-red-600">{{ $participants->where('status', 'cancelled')->count() }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">Participants List</h3>
                            <div class="flex gap-2">
                                <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attended At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($participants as $index => $participant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $participant->user->full_name ?? $participant->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $participant->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($participant->status === 'attended')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Attended
                                                </span>
                                            @elseif($participant->status === 'registered')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Registered
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>Cancelled
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($participant->is_paid)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Paid
                                                </span>
                                                @if($participant->amount_paid)
                                                    <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($participant->amount_paid, 0, ',', '.') }}</div>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Unpaid
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $participant->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($participant->attended_at)
                                                <span class="text-green-600 font-semibold">{{ \Carbon\Carbon::parse($participant->attended_at)->format('M d, Y H:i') }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No participants registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToCSV() {
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            let csv = [];

            rows.forEach((row) => {
                const cols = row.querySelectorAll('th, td');
                let rowData = [];
                cols.forEach((col) => {
                    rowData.push(col.textContent.trim());
                });
                csv.push(rowData.join(','));
            });

            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'participants-{{ $event->id }}.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>

