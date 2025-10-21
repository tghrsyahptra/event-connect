<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management - Event Connect Admin</title>
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
                <a href="/admin/users" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="/admin/events" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events
                </a>
                <a href="/admin/categories" class="flex items-center px-6 py-3 text-gray-700 bg-blue-50 border-r-4 border-blue-500">
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
                        <h2 class="text-3xl font-bold text-gray-800">Categories Management</h2>
                        <p class="text-gray-600">Manage event categories and their settings.</p>
                    </div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </button>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                <!-- Search and Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" placeholder="Search categories..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>All Categories</option>
                                <option>Active Only</option>
                                <option>Inactive Only</option>
                            </select>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($categories as $category)
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4">{{ $category->description ?? 'No description provided' }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $category->events_count }}</div>
                                    <div class="text-xs text-gray-500">Events</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $category->created_at->format('M d') }}</div>
                                    <div class="text-xs text-gray-500">Created</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Add Category Form (Hidden by default) -->
                <div class="bg-white rounded-lg shadow p-6 hidden" id="addCategoryForm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Category</h3>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter category name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                <input type="color" class="w-full h-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="#3B82F6">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" placeholder="Enter category description"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle add category form
        document.querySelector('button[class*="bg-blue-600"]').addEventListener('click', function() {
            const form = document.getElementById('addCategoryForm');
            form.classList.toggle('hidden');
        });
    </script>
</body>
</html>
