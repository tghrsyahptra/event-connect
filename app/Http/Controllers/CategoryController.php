<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Role validation is handled by middleware in routes
    }

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

        $categories = $query->withCount('events')->orderBy('name')->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories')
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

        return redirect()->route('admin.categories')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Category {$status} successfully!");
    }

    /**
     * Get category statistics
     */
    public function statistics(Category $category)
    {
        $stats = [
            'total_events' => $category->events()->count(),
            'active_events' => $category->events()->where('status', 'published')->count(),
            'total_participants' => $category->events()->withCount('participants')->get()->sum('participants_count'),
            'total_revenue' => $category->events()->sum('price'),
        ];

        return response()->json($stats);
    }
}

