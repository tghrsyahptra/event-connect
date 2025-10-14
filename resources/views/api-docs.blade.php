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
                <code class="text-green-400">http://127.0.0.1:8003/api</code>
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

        <!-- Feedback Endpoints -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Feedback & Certificates</h2>
            
            <!-- Submit Feedback -->
            <div class="border-l-4 border-blue-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/feedbacks/{event_id}</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Submit feedback for an event (required for certificate)</p>
                
                <h4 class="font-semibold mb-2">Request Body:</h4>
                <div class="code-block">
<pre><code class="language-json">{
    "rating": 5,
    "comment": "Great event! Very informative and well organized."
}</code></pre>
                </div>
            </div>

            <!-- Download Certificate -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/feedbacks/certificate/{event_id}/download</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Download event certificate (PDF)</p>
            </div>

            <!-- Get Certificate URL -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/feedbacks/certificate/{event_id}/url</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get certificate download URL</p>
            </div>

            <!-- My Feedbacks -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/feedbacks/my-feedbacks</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get user's feedbacks</p>
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
                <p class="text-gray-600 mb-3">Get user notifications</p>
            </div>

            <!-- Mark as Read -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/notifications/{id}/read</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Mark notification as read</p>
            </div>

            <!-- Mark All as Read -->
            <div class="border-l-4 border-yellow-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-post">POST</span>
                    <span class="ml-3 font-mono text-lg">/notifications/mark-all-read</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Mark all notifications as read</p>
            </div>

            <!-- Unread Count -->
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <div class="flex items-center mb-2">
                    <span class="method-get">GET</span>
                    <span class="ml-3 font-mono text-lg">/notifications/unread-count</span>
                    <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Auth Required</span>
                </div>
                <p class="text-gray-600 mb-3">Get unread notifications count</p>
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
