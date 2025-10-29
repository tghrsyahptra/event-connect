<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event Connect')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('events.index') }}" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-calendar-alt text-primary text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Event Connect</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-search mr-1"></i>Browse Events
                            </a>
                            <a href="{{ route('participant.dashboard') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                            </a>
                            <div class="relative">
                                <button class="flex items-center text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-user mr-1"></i>{{ Auth::user()->name }}
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                    <a href="{{ route('participant.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-search mr-1"></i>Browse Events
                            </a>
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                <i class="fas fa-user-plus mr-1"></i>Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Event Connect</h3>
                    <p class="text-gray-300">Discover and join amazing events in your area.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white">Browse Events</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Categories</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; 2024 Event Connect. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.relative button');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    if (menu) {
                        // Close all other dropdowns
                        document.querySelectorAll('.relative .absolute').forEach(otherMenu => {
                            if (otherMenu !== menu) {
                                otherMenu.classList.add('hidden');
                            }
                        });
                        // Toggle current dropdown
                        menu.classList.toggle('hidden');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.relative .absolute').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
