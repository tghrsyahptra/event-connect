<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $event->title }} - Event Connect</title>
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
                        <h2 class="text-3xl font-bold text-gray-800">QR Code - {{ $event->title }}</h2>
                        <p class="text-gray-600">Use this QR code for event attendance</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Events
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600">{{ $event->location }}</p>
                        <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('l, F d, Y') }}</p>
                    </div>

                    @if($event->qr_code)
                        <div class="flex flex-col md:flex-row gap-8 items-center justify-center">
                            <!-- QR Code Display -->
                            <div class="bg-white p-6 rounded-lg shadow-lg border-4 border-blue-500">
                                <img src="{{ asset('storage/' . $event->qr_code) }}" 
                                     alt="QR Code for {{ $event->title }}" 
                                     class="w-64 h-64 mx-auto">
                                <p class="text-center text-gray-600 mt-4 text-sm">Scan this QR code for attendance</p>
                            </div>

                            <!-- QR Code Info -->
                            <div class="space-y-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Instructions
                                    </h4>
                                    <ul class="list-disc list-inside text-blue-800 text-sm space-y-1">
                                        <li>Display this QR code to participants at the event</li>
                                        <li>Participants will scan using their mobile devices</li>
                                        <li>Attendance will be automatically marked upon successful scan</li>
                                        <li>You can view attendance status in the Participants page</li>
                                    </ul>
                                </div>

                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-green-900 mb-2">
                                        <i class="fas fa-users mr-2"></i>Attendance Status
                                    </h4>
                                    <div class="text-green-800 text-sm">
                                        <p><strong>Registered:</strong> {{ $event->registered_count }} participants</p>
                                        <p><strong>Attended:</strong> {{ $event->participants()->where('status', 'attended')->count() }} participants</p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('attendance.participants', $event->id) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-center transition duration-200">
                                        <i class="fas fa-list mr-2"></i>View Participants
                                    </a>
                                    <button onclick="downloadQR()" 
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                        <i class="fas fa-download mr-2"></i>Download QR
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code String (for manual entry) -->
                        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">
                                <i class="fas fa-keyboard mr-2"></i>QR Code String (for manual entry)
                            </h4>
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       id="qr-code-string" 
                                       value="{{ basename($event->qr_code, '.png') }}" 
                                       readonly
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white font-mono text-sm">
                                <button onclick="copyQRCode()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                    <i class="fas fa-copy mr-2"></i>Copy
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-qrcode text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">QR Code Not Generated</h3>
                            <p class="text-gray-600 mb-4">QR code will be generated automatically when you publish the event.</p>
                            <a href="{{ route('admin.events.edit', $event->id) }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                                Edit Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadQR() {
            const qrImage = document.querySelector('img[alt="QR Code for {{ $event->title }}"]');
            if (qrImage) {
                const link = document.createElement('a');
                link.href = qrImage.src;
                link.download = 'qr-code-{{ $event->id }}.png';
                link.click();
            }
        }

        function copyQRCode() {
            const input = document.getElementById('qr-code-string');
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            
            navigator.clipboard.writeText(input.value).then(() => {
                alert('QR code string copied to clipboard!');
            }).catch(() => {
                // Fallback for older browsers
                document.execCommand('copy');
                alert('QR code string copied to clipboard!');
            });
        }
    </script>
</body>
</html>

