@extends('participant.layout')

@section('title', 'Browse Events - Event Connect')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Discover Amazing Events</h1>
            <p class="text-xl text-gray-600">Find and join events that match your interests</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <form method="GET" action="{{ route('events.index') }}" id="searchForm">
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search events, organizers, or categories..."
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <select name="price_range" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Any Price</option>
                            <option value="free" {{ request('price_range') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="0-100000" {{ request('price_range') == '0-100000' ? 'selected' : '' }}>Under 100K</option>
                            <option value="100000-500000" {{ request('price_range') == '100000-500000' ? 'selected' : '' }}>100K - 500K</option>
                            <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>500K - 1M</option>
                            <option value="1000000+" {{ request('price_range') == '1000000+' ? 'selected' : '' }}>Above 1M</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <select name="date_filter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Any Date</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="tomorrow" {{ request('date_filter') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="next_week" {{ request('date_filter') == 'next_week' ? 'selected' : '' }}>Next Week</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" 
                               name="location" 
                               value="{{ request('location') }}"
                               placeholder="City or venue..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>

                <!-- Additional Filters -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="available_only" 
                               value="1" 
                               {{ request('available_only') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Available Only</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_paid" 
                               value="1" 
                               {{ request('is_paid') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Paid Events</span>
                    </label>
                </div>

                <!-- Sort Options -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort_by" class="border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-primary focus:border-transparent">
                            @foreach($sortOptions as $option)
                                <option value="{{ $option['key'] }}" 
                                        data-order="{{ $option['order'] }}"
                                        {{ request('sort_by') == $option['key'] && request('sort_order') == $option['order'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'asc') }}">
                    </div>
                    <button type="button" onclick="clearFilters()" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times mr-1"></i>Clear Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($events->total() > 0)
                        {{ $events->total() }} Events Found
                    @else
                        No Events Found
                    @endif
                </h2>
                @if(request()->hasAny(['search', 'category_id', 'price_range', 'date_filter', 'location', 'available_only', 'is_paid']))
                    <p class="text-gray-600 mt-1">Filtered results</p>
                @endif
            </div>
        </div>

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <!-- Event Image -->
                        <div class="h-48 bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
                            @if($event->image)
                                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center text-white">
                                    <i class="fas fa-calendar-alt text-4xl mb-2"></i>
                                    <p class="text-sm">{{ $event->category->name ?? 'Event' }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Event Content -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $event->category->color ?? '#3B82F6' }}"></div>
                                <span class="text-sm text-gray-600">{{ $event->category->name ?? 'Uncategorized' }}</span>
                            </div>

                            <!-- Event Title -->
                            <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('events.show', $event) }}" class="hover:text-primary">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <!-- Event Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                    <span>{{ $event->start_date->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    <span>{{ $event->registered_count }}/{{ $event->quota }} participants</span>
                                </div>
                            </div>

                            <!-- Price and Action -->
                            <div class="flex justify-between items-center">
                                <div class="text-lg font-bold text-primary">
                                    @if($event->price > 0)
                                        Rp {{ number_format($event->price) }}
                                    @else
                                        Free
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event) }}" 
                                   class="bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $events->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all events.</p>
                <a href="{{ route('events.index') }}" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-blue-700">
                    Browse All Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handle sort order change
    document.querySelector('select[name="sort_by"]').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const order = selectedOption.getAttribute('data-order');
        document.getElementById('sort_order').value = order;
    });

    // Clear all filters
    function clearFilters() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = false;
            } else if (input.type === 'text' || input.tagName === 'SELECT') {
                input.value = '';
            }
        });
        form.submit();
    }

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="category_id"], select[name="price_range"], select[name="date_filter"], select[name="sort_by"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });

    // Handle price range filter
    document.querySelector('select[name="price_range"]').addEventListener('change', function() {
        const value = this.value;
        if (value === 'free') {
            // Add hidden inputs for free events
            addHiddenInput('price_min', '0');
            addHiddenInput('price_max', '0');
        } else if (value.includes('-')) {
            const [min, max] = value.split('-');
            addHiddenInput('price_min', min);
            if (max !== '+') {
                addHiddenInput('price_max', max);
            }
        }
        document.getElementById('searchForm').submit();
    });

    function addHiddenInput(name, value) {
        // Remove existing hidden input
        const existing = document.querySelector(`input[name="${name}"]`);
        if (existing) {
            existing.remove();
        }
        
        // Add new hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        document.getElementById('searchForm').appendChild(input);
    }
</script>
@endsection




