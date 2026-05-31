<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /** GET /api/categories — public list */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    /** GET /api/admin/categories — full list for admin */
    public function adminIndex()
    {
        return response()->json(
            Category::withCount('products')->orderBy('sort_order')->get()
        );
    }

    /** POST /api/admin/categories */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ]);

        $data['slug'] = \Str::slug($data['name']) . '-' . uniqid();

        // Try a clean slug first
        $cleanSlug = \Str::slug($data['name']);
        if (! Category::where('slug', $cleanSlug)->exists()) {
            $data['slug'] = $cleanSlug;
        }

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    /** PUT /api/admin/categories/{id} */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ]);

        if (isset($data['name'])) {
            $newSlug = \Str::slug($data['name']);
            if (! Category::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                $data['slug'] = $newSlug;
            }
        }

        $category->update($data);

        return response()->json($category);
    }

    /** DELETE /api/admin/categories/{id} */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
