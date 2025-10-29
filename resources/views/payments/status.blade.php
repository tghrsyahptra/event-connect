<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    @if($participant->is_paid)
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    @else
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    @endif
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    @if($participant->is_paid)
                        Payment Successful!
                    @else
                        Payment Pending
                    @endif
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    @if($participant->is_paid)
                        Your payment has been processed successfully.
                    @else
                        Your payment is being processed. Please wait for confirmation.
                    @endif
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event:</span>
                        <span class="font-medium">{{ $participant->event->title }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-medium">Rp {{ number_format($participant->event->price) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($participant->is_paid) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            @if($participant->is_paid) Paid @else Pending @endif
                        </span>
                    </div>
                    
                    @if($participant->payment_reference)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference:</span>
                            <span class="font-mono text-sm">{{ $participant->payment_reference }}</span>
                        </div>
                    @endif
                    
                    @if($participant->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid At:</span>
                            <span class="font-medium">{{ $participant->paid_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>

                @if($participant->attendance_qr)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Your Attendance QR Code</h4>
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $participant->qr_code) }}" 
                                 alt="Attendance QR Code" 
                                 class="mx-auto h-32 w-32 border border-gray-300 rounded-lg">
                            <p class="mt-2 text-sm text-gray-600">
                                Use this QR code for event attendance
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('events.show', $participant->event) }}" 
                   class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                    <i class="fas fa-calendar mr-2"></i>View Event
                </a>
                <a href="{{ route('events.index') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors duration-200 text-center">
                    <i class="fas fa-list mr-2"></i>Browse Events
                </a>
            </div>

            @if(!$participant->is_paid)
                <div class="text-center">
                    <button onclick="checkPaymentStatus()" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-sync-alt mr-1"></i>Refresh Status
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        function checkPaymentStatus() {
            // Refresh the page to check latest status
            location.reload();
        }

        // Auto-refresh every 30 seconds if payment is pending
        @if(!$participant->is_paid)
            setTimeout(() => {
                location.reload();
            }, 30000);
        @endif
    </script>
</body>
</html>
