<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Event Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">Event Connect</h1>
                <p class="text-gray-600 text-sm">Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users.*') ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.events.index') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.events.*') ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.categories.*') ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-tags mr-3"></i>
                    Categories
                </a>
                <a href="{{ route('admin.analytics') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.analytics*') ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Analytics
                </a>
            </nav>
            
            <!-- Logout Button -->
            <div class="absolute bottom-0 w-64 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-6 py-3 text-gray-600 hover:bg-red-50 hover:text-red-600">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your events.')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>

