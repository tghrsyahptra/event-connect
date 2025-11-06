<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Feedbacks - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('participant.dashboard') }}" class="flex-shrink-0 flex items-center hover:opacity-80 transition-opacity">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">Event Connect</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('participant.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">My Feedbacks</h1>
            <p class="mt-2 text-gray-600">View all your event feedbacks and certificates</p>
        </div>

        @if($feedbacks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($feedbacks as $feedback)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $feedback->event->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $feedback->event->start_date->format('F j, Y') }}
                            </p>

                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">{{ $feedback->rating }}/5</span>
                            </div>

                            <!-- Comment -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $feedback->comment }}</p>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('feedback.certificate.view', $feedback->event) }}" 
                                   class="flex-1 text-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('feedback.certificate.download', $feedback->event) }}" 
                                   class="flex-1 text-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                    <i class="fas fa-download mr-1"></i>Certificate
                                </a>
                            </div>

                            <!-- Submitted Date -->
                            <p class="text-xs text-gray-400 mt-3 text-center">
                                Submitted {{ $feedback->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $feedbacks->links() }}
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-12 text-center">
                <i class="fas fa-comment-slash text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Feedbacks Yet</h3>
                <p class="text-gray-600 mb-6">You haven't submitted any feedback for events</p>
                <a href="{{ route('participant.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        @endif
    </div>
</body>
</html>