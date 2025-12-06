<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Connect API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .code-block {
            background: #2d3748;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            overflow-x: auto;
        }
        .method-get { @apply bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium; }
        .method-post { @apply bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-medium; }
        .method-put { @apply bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-medium; }
        .method-delete { @apply bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="gradient-bg text-white py-12">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-bold mb-4">Event Connect API</h1>
            <p class="text-xl opacity-90">Comprehensive API Documentation for Event Management System</p>
            <div class="mt-4 flex items-center space-x-4">
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">Laravel 12.33.0</span>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">MySQL</span>
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">Sanctum Auth</span>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Base URL -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Base URL</h2>
            <div class="code-block">
                <code class="text-green-400">http://localhost:8000/api</code>
            </div>
        </div>

        <!-- Authentication -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Authentication</h2>
            <p class="text-gray-600 mb-4">This API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected endpoints.</p>
            <div class="code-block">
                <code class="text-blue-400">Authorization: Bearer {your-token-here}</code>
            </div>
        </div>

        <!-- Authentication Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Authentication Endpoints</h2>
            
            <!-- Register -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/auth/register</span>
                </div>
                <p class="text-gray-600 mb-3">Register a new user account</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "full_name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-10-14T09:22:29.000000Z",
            "updated_at": "2025-10-14T09:22:29.000000Z"
        },
        "token": "1|hy0riaMP1xTEodrVb4a75xzutTKKMg2RitwI3eA97940a713",
        "token_type": "Bearer"
    }
}</code></pre>
                </div>
            </div>

            <!-- Login -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/auth/login</span>
                </div>
                <p class="text-gray-600 mb-3">Login with email and password</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "email": "john@example.com",
    "password": "password123"
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "full_name": "John Doe",
            "email": "john@example.com",
            "phone": null,
            "bio": null,
            "avatar": null,
            "is_organizer": false,
            "email_verified_at": null,
            "created_at": "2025-10-14T09:22:29.000000Z",
            "updated_at": "2025-10-14T09:22:29.000000Z"
        },
        "token": "2|Yf53sZr53IWX4LL95axxguF8SuuhgnUNqaaYM8212f174190",
        "token_type": "Bearer"
    }
}</code></pre>
                </div>
            </div>

            <!-- Logout -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/auth/logout</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Logout and invalidate current token</p>
            </div>

            <!-- Me -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/auth/me</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get current authenticated user information</p>
            </div>
        </div>

        <!-- Profile Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Profile Management</h2>
            
            <!-- Get Profile -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/profile</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get user profile information</p>
            </div>

            <!-- Update Profile -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-put">PUT</span>
                    <span class="ml-3 font-mono text-lg">/profile</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Update user profile information</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "full_name": "John Updated",
    "phone": "+1234567890",
    "bio": "Event enthusiast and organizer",
    "avatar": "path/to/avatar.jpg"
}</code></pre>
                </div>
            </div>

            <!-- Change Password -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/profile/change-password</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Change user password</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Event Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Event Management</h2>
            
            <!-- Get Events (Homepage) -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events</span>
                </div>
                <p class="text-gray-600 mb-3">Get all published events (homepage)</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">?search=tech&category=1&is_paid=false&date=2025-10-15&page=1</code></pre>
                </div>
            </div>

            <!-- Get Event Details -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events/{id}</span>
                </div>
                <p class="text-gray-600 mb-3">Get specific event details</p>
            </div>

            <!-- Create Event -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/events</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Create a new event</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "title": "Tech Conference 2025",
    "description": "Annual technology conference",
    "location": "Convention Center, Jakarta",
    "start_date": "2025-12-15 09:00:00",
    "end_date": "2025-12-15 17:00:00",
    "category_id": 1,
    "is_paid": true,
    "price": 150000,
    "quota": 100,
    "image": "path/to/image.jpg"
}</code></pre>
                </div>
            </div>

            <!-- Update Event -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-put">PUT</span>
                    <span class="ml-3 font-mono text-lg">/events/{id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Update event information</p>
            </div>

            <!-- Delete Event -->
            <div class="border-l-4 border-red-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-delete">DELETE</span>
                    <span class="ml-3 font-mono text-lg">/events/{id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Delete an event</p>
            </div>

            <!-- My Events -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events/my-events</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get events created by current user</p>
            </div>

            <!-- Participating Events -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events/participating</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get events user is participating in</p>
            </div>
        </div>

        <!-- Event Participation Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Event Participation</h2>
            
            <!-- Join Event -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/participants/join/{event_id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Join an event</p>
            </div>

            <!-- Cancel Participation -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/participants/cancel/{event_id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Cancel event participation</p>
            </div>

            <!-- Mark Attendance -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/participants/attendance</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Mark attendance via QR code scan</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "event_id": 1,
    "qr_data": "event_qr_code_data"
}</code></pre>
                </div>
            </div>

            <!-- My Participations -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/participants/my-participations</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get user's event participations</p>
            </div>
        </div>

         <!-- Feedback Summary Endpoints (NEW) -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Feedback Summary (AI-Generated)</h2>
            <p class="text-gray-600 mb-4">AI-powered feedback analysis and summary generation for event organizers</p>
            
            <!-- Generate Summary -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/events/{event_id}/feedback/generate-summary</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Generate AI summary for feedback event. Event must be ended, minimum 1 feedback required, and summary hasn't been generated before.</p>
                
                <h4 class="font-semibold mb-2">Response (201):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Feedback summary generated successfully",
    "data": {
        "summary": "Overall, participants enjoyed the event. The venue was praised for its accessibility and comfort...",
        "generated_at": "2024-12-06T10:30:00.000000Z",
        "feedback_count": 15,
        "average_rating": 4.5
    }
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Error Responses:</h4>
                <div class="code-block">
<pre><code class="language-json">// 400 - Event not ended yet
{
    "success": false,
    "message": "Cannot generate summary. Event has not ended yet."
}

// 400 - Summary already exists
{
    "success": false,
    "message": "Summary already generated for this event. Each event can only have one summary."
}

// 400 - Not enough feedback
{
    "success": false,
    "message": "Need at least 1 feedbacks to generate summary. Current: 0"
}</code></pre>
                </div>
            </div>

            <!-- Get Summary -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events/{event_id}/feedback/summary</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Get AI-generated summary with statistics</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "summary": "Overall, participants enjoyed the event...",
        "generated_at": "2024-12-06T10:30:00.000000Z",
        "feedback_count": 15,
        "current_feedback_count": 20,
        "average_rating": 4.5,
        "rating_distribution": {
            "5_star": 10,
            "4_star": 5,
            "3_star": 3,
            "2_star": 1,
            "1_star": 1
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get Detailed Summary -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/events/{event_id}/feedback/summary/detailed</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Get detailed summary with all feedbacks and complete statistics</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "summary": "Overall, participants enjoyed the event...",
        "generated_at": "2024-12-06T10:30:00.000000Z",
        "statistics": {
            "total_feedbacks": 20,
            "feedback_count_at_summary": 15,
            "average_rating": 4.5,
            "rating_distribution": {
                "5_star": 10,
                "4_star": 5,
                "3_star": 3,
                "2_star": 1,
                "1_star": 1
            }
        },
        "feedbacks": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Great event! Very informative and well organized.",
                "created_at": "2024-12-06T10:30:00.000000Z",
                "user": {
                    "name": "John Doe",
                    "email": "john@example.com"
                }
            }
        ]
    }
}</code></pre>
                </div>
            </div>

            <!-- Update Summary -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-put">PUT</span>
                    <span class="ml-3 font-mono text-lg">/events/{event_id}/feedback/summary</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Regenerate AI summary with latest feedbacks. Previous summary will be replaced.</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Feedback summary updated successfully",
    "data": {
        "summary": "Updated summary based on all feedbacks...",
        "generated_at": "2024-12-06T11:00:00.000000Z",
        "feedback_count": 20,
        "average_rating": 4.6,
        "previous_feedback_count": 15
    }
}</code></pre>
                </div>
            </div>

            <!-- Delete Summary -->
            <div class="border-l-4 border-red-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-delete">DELETE</span>
                    <span class="ml-3 font-mono text-lg">/events/{event_id}/feedback/summary</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Delete AI-generated summary. Can be regenerated after deletion.</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Feedback summary deleted successfully"
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Notification Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Notifications</h2>
            
            <!-- Get Notifications -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/notifications</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get user notifications with pagination</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">?unread_only=true&page=1</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "type": "event_reminder",
                "title": "Event Reminder",
                "message": "Reminder: Tech Conference 2024 on 15 Dec 2024",
                "is_read": false,
                "created_at": "2024-12-06T10:30:00.000000Z",
                "event": {
                    "id": 1,
                    "title": "Tech Conference 2024"
                }
            }
        ],
        "per_page": 20,
        "total": 100
    }
}</code></pre>
                </div>
            </div>

            <!-- Mark as Read -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/notifications/{id}/read</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Mark notification as read</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Notification marked as read"
}</code></pre>
                </div>
            </div>

            <!-- Mark All as Read -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/notifications/mark-all-read</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Mark all notifications as read</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "All notifications marked as read"
}</code></pre>
                </div>
            </div>

            <!-- Unread Count -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/notifications/unread-count</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get unread notifications count</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "unread_count": 5
    }
}</code></pre>
                </div>
            </div>

            <!-- Delete Notification -->
            <div class="border-l-4 border-red-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-delete">DELETE</span>
                    <span class="ml-3 font-mono text-lg">/notifications/{id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Delete a notification</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Notification deleted successfully"
}</code></pre>
                </div>
            </div>

            <!-- Send Event Reminder (Manual) -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/notifications/send-reminder</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Send email reminder to event participants manually (for testing or admin trigger)</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "event_id": 1,
    "user_id": 24  // Optional: send to specific user only
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response (200) - All participants:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Event reminders sent successfully to 10 participant(s)"
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response (200) - Specific user:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Event reminder sent successfully to john@example.com"
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Error Responses:</h4>
                <div class="code-block">
<pre><code class="language-json">// 404 - No participants found
{
    "success": false,
    "message": "No participants found for this event"
}

// 500 - Failed to send
{
    "success": false,
    "message": "Failed to send event reminder: {error_message}"
}</code></pre>
                </div>
            </div>

            <!-- Get Upcoming Reminders -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/notifications/upcoming-reminders</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get upcoming events in next 7 days that need reminder</p>
                
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": [
        {
            "event_id": 1,
            "event_title": "Tech Conference 2024",
            "start_date": "2024-12-15T09:00:00.000000Z",
            "end_date": "2024-12-15T17:00:00.000000Z",
            "days_until_event": 3,
            "location": "Jakarta Convention Center",
            "event_type": "online",
            "contact_info": "info@techconf.com"
        }
    ]
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Category Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Categories</h2>
            
            <!-- Get Categories -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/categories</span>
                </div>
                <p class="text-gray-600 mb-3">Get all active categories</p>
                
                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Technology",
            "description": "Tech conferences, workshops, and meetups",
            "color": "#3B82F6",
            "is_active": true,
            "created_at": "2025-10-14T09:13:18.000000Z",
            "updated_at": "2025-10-14T09:13:18.000000Z"
        }
    ]
}</code></pre>
                </div>
            </div>

            <!-- Create Category -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/categories</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Admin Only</span>
                </div>
                <p class="text-gray-600 mb-3">Create a new category</p>
            </div>

            <!-- Update Category -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-put">PUT</span>
                    <span class="ml-3 font-mono text-lg">/categories/{id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Admin Only</span>
                </div>
                <p class="text-gray-600 mb-3">Update category information</p>
            </div>

            <!-- Delete Category -->
            <div class="border-l-4 border-red-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-delete">DELETE</span>
                    <span class="ml-3 font-mono text-lg">/categories/{id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Admin Only</span>
                </div>
                <p class="text-gray-600 mb-3">Delete a category</p>
            </div>
        </div>

        <!-- Error Responses -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Error Responses</h2>
            
            <div class="mb-4">
                <h4 class="font-semibold mb-2">Validation Error (422):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}</code></pre>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Unauthorized (401):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Forbidden (403):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "message": "This action is unauthorized."
}</code></pre>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Not Found (404):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "message": "The route api/events/999 could not be found."
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Status Codes -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">HTTP Status Codes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium mr-3">200</span>
                    <span>Success</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium mr-3">401</span>
                    <span>Unauthorized</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium mr-3">403</span>
                    <span>Forbidden</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium mr-3">404</span>
                    <span>Not Found</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-medium mr-3">422</span>
                    <span>Validation Error</span>
                </div>
                <div class="flex items-center">
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium mr-3">500</span>
                    <span>Internal Server Error</span>
                </div>
            </div>
        </div>

        <!-- Payment History API -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Payment History API</h2>
            
            <!-- Get Payment History -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/payments/history</span>
                </div>
                <p class="text-gray-600 mb-3">Get payment history for authenticated user</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "status": "paid|pending|failed|cancelled",     // Optional
    "payment_method": "invoice|virtual_account|ewallet", // Optional
    "date_from": "2025-01-01",                    // Optional
    "date_to": "2025-12-31",                      // Optional
    "per_page": 10                                // Optional, default: 10
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "payments": [
            {
                "id": 93,
                "event": {
                    "id": 9,
                    "title": "Digital Art Exhibition",
                    "start_date": "2025-11-24T16:25:39.000000Z",
                    "location": "Jakarta Art Gallery",
                    "price": "50000.00",
                    "organizer": {
                        "id": 3,
                        "name": "Sarah Johnson",
                        "email": "sarah@workshop.com"
                    },
                    "category": {
                        "id": 5,
                        "name": "Arts & Culture",
                        "color": "#8B5CF6"
                    }
                },
                "payment": {
                    "reference": "69024d448a9cf659daae6855",
                    "method": "invoice",
                    "status": "paid",
                    "amount": 50000,
                    "is_paid": true,
                    "paid_at": "2025-10-29T17:25:00.000000Z"
                },
                "participation": {
                    "status": "registered",
                    "attended_at": null,
                    "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
                    "qr_code_url": "http://localhost:8000/storage/qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 10,
            "total": 2,
            "from": 1,
            "to": 2,
            "has_more_pages": false
        },
        "summary": {
            "total_payments": 2,
            "total_paid": 100000,
            "total_pending": 0,
            "total_failed": 0
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get Payment Statistics -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/payments/statistics</span>
                </div>
                <p class="text-gray-600 mb-3">Get payment statistics for authenticated user</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "date_from": "2024-01-01",    // Optional, default: 12 months ago
    "date_to": "2025-12-31"       // Optional, default: now
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "period": {
            "from": "2024-01-01T00:00:00.000000Z",
            "to": "2025-12-31T23:59:59.000000Z"
        },
        "status_breakdown": {
            "paid": {"count": 5, "total_amount": 250000},
            "pending": {"count": 2, "total_amount": 100000},
            "failed": {"count": 1, "total_amount": 50000}
        },
        "method_breakdown": {
            "invoice": {"count": 6, "total_amount": 300000},
            "virtual_account": {"count": 2, "total_amount": 100000}
        },
        "monthly_trends": [
            {"month": "2025-01", "count": 2, "total_amount": 100000},
            {"month": "2025-02", "count": 3, "total_amount": 150000}
        ],
        "category_breakdown": [
            {"category_name": "Technology", "count": 4, "total_amount": 200000},
            {"category_name": "Arts & Culture", "count": 2, "total_amount": 100000}
        ],
        "summary": {
            "total_payments": 8,
            "total_amount": 400000,
            "average_amount": 50000,
            "success_rate": 87.5
        }
    }
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Xendit Payment Gateway API -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Xendit Payment Gateway API</h2>
            
            <!-- Create Payment -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/payments/create</span>
                </div>
                <p class="text-gray-600 mb-3">Create payment for event using Xendit</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "event_id": 9,
    "payment_method": "invoice",
    "bank_code": "BCA",           // Optional: untuk virtual_account
    "ewallet_type": "OVO"         // Optional: untuk ewallet
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Payment Methods:</h4>
                <ul class="list-disc list-inside text-gray-600 mb-4">
                    <li><code>invoice</code> - Credit Card (Snap Xendit)</li>
                    <li><code>virtual_account</code> - Virtual Account (BCA, BNI, BRI, MANDIRI)</li>
                    <li><code>ewallet</code> - E-Wallet (OVO, DANA, LINKAJA, SHOPEEPAY)</li>
                </ul>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Payment created successfully",
    "data": {
        "participant": {
            "id": 93,
            "user_id": 24,
            "event_id": 9,
            "status": "registered",
            "is_paid": false,
            "amount_paid": null,
            "payment_reference": "690251be8a9cf659daae6bcb",
            "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
            "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
            "payment_url": null,
            "payment_status": "pending",
            "payment_method": null,
            "attended_at": null,
            "created_at": "2025-10-29T17:05:28.000000Z",
            "updated_at": "2025-10-29T17:22:13.000000Z"
        },
        "payment_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
        "payment_reference": "690251be8a9cf659daae6bcb",
        "payment_method": "invoice",
        "event": {
            "id": 9,
            "title": "Digital Art Exhibition",
            "start_date": "2025-11-24T16:25:39.000000Z",
            "location": "Jakarta Art Gallery"
        },
        "attendance_qr": {
            "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
            "qr_code_url": "http://localhost:8000/storage/qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
            "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
            "message": "Use this QR code for attendance check-in at the event"
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get Payment Methods -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/payments/methods</span>
                </div>
                <p class="text-gray-600 mb-3">Get available payment methods</p>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "invoice": {
            "name": "Credit Card",
            "description": "Pay with credit card via Xendit",
            "icon": "credit-card"
        },
        "virtual_account": {
            "name": "Virtual Account",
            "description": "Pay via bank transfer",
            "banks": ["BCA", "BNI", "BRI", "MANDIRI"]
        },
        "ewallet": {
            "name": "E-Wallet",
            "description": "Pay with digital wallet",
            "providers": ["OVO", "DANA", "LINKAJA", "SHOPEEPAY"]
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Xendit Webhook -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/payments/webhook</span>
                </div>
                <p class="text-gray-600 mb-3">Xendit webhook endpoint (called by Xendit)</p>
                
                <h4 class="font-semibold mb-2">Headers:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "X-Xendit-Signature": "{webhook_signature}",
    "Content-Type": "application/json"
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Webhook Payload (from Xendit):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "id": "690251be8a9cf659daae6bcb",
    "external_id": "event_9_participant_93_1761757593_69024999af04a",
    "user_id": "5e8b4c4b4b4b4b4b4b4b4b4b",
    "status": "PAID",
    "merchant_name": "Event Connect",
    "amount": 50000,
    "description": "Payment for event: Digital Art Exhibition",
    "invoice_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
    "expiry_date": "2025-10-30T17:22:13.000Z",
    "created": "2025-10-29T17:22:13.000Z",
    "updated": "2025-10-29T17:25:00.000Z",
    "currency": "IDR",
    "paid_at": "2025-10-29T17:25:00.000Z",
    "payment_method": "CREDIT_CARD",
    "payment_channel": "CREDIT_CARD",
    "payment_destination": "CREDIT_CARD"
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Super Admin API -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Super Admin API</h2>
            <p class="text-gray-600 mb-4">APIs for Super Admin to manage all event organizers and events</p>
            
            <!-- Get Event Organizers -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/super-admin/organizers</span>
                </div>
                <p class="text-gray-600 mb-3">Get all event organizers with their events</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "search": "john",                    // Optional: search by name/email
    "status": "active|inactive",         // Optional: filter by status
    "per_page": 10                       // Optional, default: 10
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "organizers": [
            {
                "id": 3,
                "name": "Sarah Johnson",
                "email": "sarah@workshop.com",
                "phone": "+6281234567890",
                "bio": "Event management expert",
                "avatar": null,
                "role": "admin",
                "is_organizer": true,
                "created_at": "2025-10-14T09:22:29.000000Z",
                "updated_at": "2025-10-14T09:22:29.000000Z",
                "events": {
                    "total": 5,
                    "published": 3,
                    "draft": 1,
                    "completed": 1,
                    "cancelled": 0,
                    "total_participants": 150,
                    "total_revenue": 750000,
                    "recent_events": [
                        {
                            "id": 9,
                            "title": "Digital Art Exhibition",
                            "status": "published",
                            "start_date": "2025-11-24T16:25:39.000000Z",
                            "location": "Jakarta Art Gallery",
                            "price": 50000,
                            "is_paid": true,
                            "registered_count": 25,
                            "quota": 100,
                            "category": {
                                "id": 5,
                                "name": "Arts & Culture",
                                "color": "#8B5CF6"
                            },
                            "created_at": "2025-10-29T16:25:39.000000Z"
                        }
                    ]
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 10,
            "total": 5,
            "from": 1,
            "to": 5,
            "has_more_pages": false
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get All Events -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/super-admin/events</span>
                </div>
                <p class="text-gray-600 mb-3">Get all events from all organizers</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "search": "conference",              // Optional: search by title/description
    "status": "published|draft|completed|cancelled", // Optional
    "category_id": 1,                    // Optional: filter by category
    "organizer_id": 3,                   // Optional: filter by organizer
    "date_from": "2025-01-01",           // Optional
    "date_to": "2025-12-31",             // Optional
    "per_page": 10                       // Optional, default: 10
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "events": [
            {
                "id": 9,
                "title": "Digital Art Exhibition",
                "description": "Contemporary digital art exhibition...",
                "location": "Jakarta Art Gallery",
                "start_date": "2025-11-24T16:25:39.000000Z",
                "end_date": "2025-11-24T18:25:39.000000Z",
                "price": "50000.00",
                "is_paid": true,
                "quota": 100,
                "registered_count": 25,
                "status": "published",
                "is_active": true,
                "image": "events/digital-art-exhibition.jpg",
                "qr_code": "qr_codes/event_1234567890_abc123.svg",
                "created_at": "2025-10-29T16:25:39.000000Z",
                "updated_at": "2025-10-29T16:25:39.000000Z",
                "organizer": {
                    "id": 3,
                    "name": "Sarah Johnson",
                    "email": "sarah@workshop.com",
                    "phone": "+6281234567890",
                    "role": "admin"
                },
                "category": {
                    "id": 5,
                    "name": "Arts & Culture",
                    "color": "#8B5CF6"
                },
                "participants": {
                    "total": 25,
                    "attended": 15,
                    "registered": 10,
                    "cancelled": 0
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 10,
            "total": 25,
            "from": 1,
            "to": 10,
            "has_more_pages": true
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get Statistics -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/super-admin/statistics</span>
                </div>
                <p class="text-gray-600 mb-3">Get overall statistics for all organizers and events</p>
                
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "date_from": "2024-01-01",    // Optional, default: 12 months ago
    "date_to": "2025-12-31"       // Optional, default: now
}</code></pre>
                </div>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "statistics": {
            "total_organizers": 15,
            "total_events": 150,
            "total_participants": 5000,
            "total_revenue": 25000000
        },
        "period_statistics": {
            "organizers": 10,
            "events": 75,
            "participants": 2500,
            "revenue": 12500000
        },
        "event_status_breakdown": {
            "published": 100,
            "draft": 25,
            "completed": 20,
            "cancelled": 5
        },
        "monthly_trends": [
            {"month": "2025-01", "count": 10, "total_amount": 5000000},
            {"month": "2025-02", "count": 15, "total_amount": 7500000}
        ],
        "top_organizers": [
            {
                "id": 3,
                "name": "Sarah Johnson",
                "email": "sarah@workshop.com",
                "events_count": 25
            }
        ],
        "category_breakdown": [
            {"name": "Technology", "count": 50, "total_amount": 10000000},
            {"name": "Arts & Culture", "count": 30, "total_amount": 6000000}
        ]
    }
}</code></pre>
                </div>
            </div>

            <!-- Toggle Organizer Status -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/super-admin/organizers/{id}/toggle-status</span>
                </div>
                <p class="text-gray-600 mb-3">Toggle organizer status (activate/deactivate)</p>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "Organizer status updated successfully",
    "data": {
        "user": {
            "id": 3,
            "name": "Sarah Johnson",
            "email": "sarah@workshop.com",
            "is_organizer": true
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Get Organizer Details -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/super-admin/organizers/{id}</span>
                </div>
                <p class="text-gray-600 mb-3">Get detailed information about a specific organizer</p>

                <h4 class="font-semibold mb-2">Response:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "id": 3,
        "name": "Sarah Johnson",
        "email": "sarah@workshop.com",
        "phone": "+6281234567890",
        "bio": "Event management expert",
        "avatar": null,
        "role": "admin",
        "is_organizer": true,
        "created_at": "2025-10-14T09:22:29.000000Z",
        "events": [
            {
                "id": 9,
                "title": "Digital Art Exhibition",
                "description": "Contemporary digital art exhibition...",
                "location": "Jakarta Art Gallery",
                "start_date": "2025-11-24T16:25:39.000000Z",
                "end_date": "2025-11-24T18:25:39.000000Z",
                "price": "50000.00",
                "is_paid": true,
                "quota": 100,
                "registered_count": 25,
                "status": "published",
                "is_active": true,
                "category": {
                    "id": 5,
                    "name": "Arts & Culture",
                    "color": "#8B5CF6"
                },
                "participants": {
                    "total": 25,
                    "attended": 15,
                    "registered": 10,
                    "cancelled": 0
                },
                "created_at": "2025-10-29T16:25:39.000000Z"
            }
        ]
    }
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Admin Dashboard API -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Admin Dashboard API</h2>
            <p class="text-gray-600 mb-4">API untuk organizer (role <code>admin</code>) untuk melihat ringkasan aktivitas dan kinerja event mereka</p>

            <!-- Get Dashboard Overview -->
            <div class="border-l-4 border-purple-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/admin/dashboard</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Get organizer dashboard overview: stats, recent activities, monthly events, top events, and category breakdown</p>
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "stats": {
            "total_users": 120,
            "total_events": 8,
            "total_categories": 5,
            "total_participants": 340,
            "active_events": 3,
            "completed_events": 5,
            "this_month_events": 2,
            "this_month_participants": 45
        },
        "recent_activities": [
            {
                "type": "user_joined",
                "message": "User John Doe joined 'Tech Conference 2025'",
                "time": "2025-10-29T17:05:28.000000Z",
                "icon": "user-plus",
                "color": "green"
            }
        ],
        "monthly_events": {
            "months": ["Jan 2025", "Feb 2025", ..., "Oct 2025"],
            "events": [1, 0, 2, 1, 0, 1, 2, 0, 1, 0, 0, 2]
        },
        "category_stats": [
            {
                "id": 1,
                "name": "Technology",
                "count": 5,
                "color": "#3B82F6"
            }
        ],
        "top_events": [
            {
                "id": 9,
                "title": "Digital Art Exhibition",
                "participants_count": 25,
                "organizer": {
                    "id": 3,
                    "full_name": "Sarah Johnson"
                },
                "category": {
                    "id": 5,
                    "name": "Arts & Culture",
                    "color": "#8B5CF6"
                }
            }
        ]
    }
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Analytics API -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Analytics API</h2>
            <p class="text-gray-600 mb-4">API untuk organizer (role <code>admin</code>) untuk melihat analitik kinerja event mereka dalam rentang waktu tertentu</p>

            <!-- Get Analytics Overview -->
            <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/analytics</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Get detailed analytics: key metrics, monthly/event/user/revenue trends, category & top events</p>
                <h4 class="font-semibold mb-2">Query Parameters:</h4>
                <div class="code-block">
<pre><code class="language-json">?date_range=30  // Days (default: 30)</code></pre>
                </div>
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "data": {
        "stats": {
            "total_revenue": 500000,
            "avg_rating": 4.5,
            "conversion_rate": 87.5,
            "active_users": 120,
            "new_users": 30,
            "total_participants": 340,
            "total_feedbacks": 20
        },
        "monthlyTrends": [
            {
                "month": "Jan 2025",
                "events": 2
            }
        ],
        "categoryAnalytics": [
            {
                "name": "Technology",
                "events_count": 4,
                "color": "#3B82F6"
            }
        ],
        "userAnalytics": {
            "user_trends": [
                {"date": "2025-10-29", "count": 15}
            ]
        },
        "eventAnalytics": {
            "event_trends": [
                {"date": "2025-10-28", "count": 1}
            ]
        },
        "revenueAnalytics": {
            "revenue_trends": [
                {"date": "2025-10-29", "revenue": 50000}
            ]
        },
        "topEvents": [
            {
                "title": "Digital Art Exhibition",
                "participants_count": 25,
                "price": 50000,
                "category": {
                    "name": "Arts & Culture",
                    "color": "#8B5CF6"
                }
            }
        ],
        "date_range": {
            "days": 30,
            "start_date": "2025-10-07",
            "end_date": "2025-11-06"
        }
    }
}</code></pre>
                </div>
            </div>

            <!-- Export Analytics -->
            <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/analytics/export</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                    <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Organizer Only</span>
                </div>
                <p class="text-gray-600 mb-3">Export analytics data as PDF (only PDF supported)</p>
                <h4 class="font-semibold mb-2">Request Body (optional):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "date_range": 30
}</code></pre>
                </div>
                <h4 class="font-semibold mb-2">Response (200):</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "success": true,
    "message": "PDF exported successfully",
    "data": {
        "url": "http://localhost:8000/storage/exports/analytics_export_2025-12-06_143022.pdf",
        "filename": "analytics_export_2025-12-06_143022.pdf"
    }
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 py-8">
            <p>Event Connect API Documentation - Generated on {{ date('Y-m-d H:i:s') }}</p>
            <p class="mt-2">Built with Laravel 12.33.0 & Tailwind CSS</p>
        </div>
    </div>

    <script>
        // Add copy functionality to code blocks
        document.querySelectorAll('.code-block').forEach(block => {
            block.addEventListener('click', () => {
                const text = block.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    // Show temporary feedback
                    const originalBg = block.style.backgroundColor;
                    block.style.backgroundColor = '#4CAF50';
                    setTimeout(() => {
                        block.style.backgroundColor = originalBg;
                    }, 200);
                });
            });
        });
    </script>
</body>
</html>


