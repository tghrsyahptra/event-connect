<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

// Sorting
if ($request->has('sort') && $request->sort) {
    switch ($request->sort) {
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'name_desc':
            $query->orderBy('name', 'desc');
            break;
        case 'events_desc':
            $query->withCount('events')->orderBy('events_count', 'desc');
            break;
        case 'events_asc':
            $query->withCount('events')->orderBy('events_count', 'asc');
            break;
        case 'active':
            $query->orderByDesc('is_active')->orderBy('name', 'asc');
            break;
        case 'inactive':
            $query->orderBy('is_active', 'asc')->orderBy('name', 'asc');
            break;

        default:
            $query->orderBy('name', 'asc');
            break;
    }
} else {
    $query->orderBy('name', 'asc');
}


        $categories = $query->withCount('events')->orderBy('name')->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.store')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')
        ->with('success', 'Category updated successfully!');

    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has events
        if ($category->events()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated events!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}