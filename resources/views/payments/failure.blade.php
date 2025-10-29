<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Failure Icon -->
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                    <i class="fas fa-times text-4xl text-red-600"></i>
                </div>

                <!-- Failure Message -->
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Payment Failed</h2>
                <p class="text-lg text-gray-600 mb-8">We're sorry, but your payment could not be processed.</p>

                @if($participant && $event)
                    <!-- Event Details -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Event Details</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Event:</span>
                                <span class="font-medium text-gray-900">{{ $event->title }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium text-gray-900">{{ $event->start_date->format('M d, Y H:i') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span class="font-medium text-gray-900">{{ $event->location }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($event->price) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Failed
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Reference -->
                    @if($participant->payment_reference)
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <p class="text-sm text-gray-600 mb-2">Payment Reference:</p>
                            <p class="font-mono text-sm text-gray-900">{{ $participant->payment_reference }}</p>
                        </div>
                    @endif
                @endif

                <!-- Possible Reasons -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">Possible reasons for payment failure:</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Insufficient funds</li>
                        <li>• Incorrect payment details</li>
                        <li>• Network connection issues</li>
                        <li>• Payment method not supported</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    @if($participant)
                        <button onclick="retryPayment()" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-redo mr-2"></i>Retry Payment
                        </button>
                    @endif
                    
                    <a href="{{ route('events.index') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>Browse Other Events
                    </a>
                    
                    @auth
                        <a href="{{ route('participant.dashboard') }}" 
                           class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                        </a>
                    @endauth
                </div>

                <!-- Support Info -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Need help? Contact our support team at 
                        <a href="mailto:support@eventconnect.com" class="text-blue-600 hover:text-blue-500">
                            support@eventconnect.com
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function retryPayment() {
            @if($participant)
                // Redirect to event page to retry payment
                window.location.href = "{{ route('events.show', $event) }}";
            @else
                window.location.href = "{{ route('events.index') }}";
            @endif
        }
    </script>
</body>
</html>




