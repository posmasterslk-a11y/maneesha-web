<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /** GET /api/categories — public list */
    public function index(Request $request)
    {
        $query = Category::where('is_active', true)
            ->whereHas('products', function ($q) {
                $q->where('is_active', true);
            })
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order');

        if ($request->query('featured')) {
            $query->where('is_featured', true);
        }

        $categories = $query->get();

        return response()->json($categories);
    }

    /** GET /api/admin/categories — full list for admin */
    public function adminIndex(Request $request)
    {
        $query = Category::withCount('products')->orderBy('sort_order');
        
        if ($request->query('all')) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate(10));
    }

    /** POST /api/admin/categories */
    public function store(Request $request)
    {
        \Log::info('Store category', ['hasFile' => $request->hasFile('size_chart_image'), 'all' => $request->all()]);
        
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['slug'] = \Str::slug($data['name']) . '-' . uniqid();

        // Try a clean slug first
        $cleanSlug = \Str::slug($data['name']);
        if (! Category::where('slug', $cleanSlug)->exists()) {
            $data['slug'] = $cleanSlug;
        }

        if ($request->hasFile('size_chart_image')) {
            $data['size_chart_image'] = $request->file('size_chart_image')->store('size_charts', 'public');
        }

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    /** PUT /api/admin/categories/{id} */
    public function update(Request $request, $id)
    {
        \Log::info('Update category payload', $request->all());
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (isset($data['name'])) {
            $newSlug = \Str::slug($data['name']);
            if (! Category::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                $data['slug'] = $newSlug;
            }
        }

        if ($request->hasFile('size_chart_image')) {
            if ($category->size_chart_image) {
                \Storage::disk('public')->delete($category->size_chart_image);
            }
            $data['size_chart_image'] = $request->file('size_chart_image')->store('size_charts', 'public');
        }

        $category->update($data);

        return response()->json($category);
    }

    /** DELETE /api/admin/categories/{id} */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->size_chart_image) {
            \Storage::disk('public')->delete($category->size_chart_image);
        }
        
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
