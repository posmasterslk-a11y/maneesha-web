<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /** GET /api/products — public product listing with optional category filter */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('hero_slider')) {
            $query->where('in_hero_slider', true);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        $products = $query->orderBy('sort_order')->paginate(12);
        $products->through(fn($p) => $this->formatProduct($p));

        return response()->json($products);
    }

    /** GET /api/products/{slug} — single product detail */
    public function show($slug)
    {
        $product = Product::with(['category', 'variants' => fn($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views');

        return response()->json($this->formatProduct($product));
    }

    /** GET /api/products/popular — get top viewed products */
    public function popular()
    {
        $products = Product::with(['category', 'variants' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();
            
        $products->transform(fn($p) => $this->formatProduct($p));

        return response()->json($products);
    }

    /** GET /api/admin/dashboard/products-stats — get stats for inventory dashboard */
    public function dashboardStats()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::whereHas('variants', function($q) {
            $q->where('stock', '<', 5);
        })->orWhere('stock', '<', 5)->count();

        return response()->json([
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'lowStockProducts' => $lowStockProducts
        ]);
    }

    /** GET /api/admin/products — full list for admin */
    public function adminIndex()
    {
        $products = Product::with(['category', 'variants'])->orderBy('sort_order')->paginate(10);
        $products->through(fn($p) => $this->formatProduct($p));

        return response()->json($products);
    }

    /** POST /api/admin/products */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:200',
            'category_id'       => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'base_price'        => 'required|numeric|min:0',
            'stock'             => 'integer|min:0',
            'fabric'            => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'sort_order'        => 'integer',
        ]);

        // Boolean from FormData strings '1'/'0'
        $data['is_active']      = $request->input('is_active', '1')   === '1';
        $data['is_featured']    = $request->input('is_featured', '0') === '1';
        $data['in_hero_slider'] = $request->input('in_hero_slider', '0') === '1';

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('images/products', 'public');
            $data['main_image'] = 'storage/' . $path;
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('images/products', 'public');
                $galleryPaths[] = 'storage/' . $path;
            }
        }
        $data['gallery_images'] = $galleryPaths;

        $slug = \Str::slug($data['name']);
        $data['slug'] = Product::where('slug', $slug)->exists()
            ? $slug . '-' . uniqid()
            : $slug;

        $product = Product::create($data);

        // Variants sent as JSON string from FormData
        $variants = json_decode($request->input('variants', '[]'), true) ?? [];
        foreach ($variants as $v) {
            if (empty($v['size'])) continue;
            $product->variants()->create([
                'size'      => $v['size'],
                'price'     => $v['price'] ?? $data['base_price'],
                'stock'     => $v['stock'] ?? 0,
                'is_active' => true,
            ]);
        }

        return response()->json($this->formatProduct($product->load(['category', 'variants'])), 201);
    }

    /** POST /api/admin/products/{id} (PUT via FormData) */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'              => 'sometimes|string|max:200',
            'category_id'       => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'base_price'        => 'sometimes|numeric|min:0',
            'stock'             => 'integer|min:0',
            'fabric'            => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'sort_order'        => 'integer',
        ]);

        // Boolean from FormData strings
        if ($request->has('is_active'))      $data['is_active']      = $request->input('is_active')      === '1';
        if ($request->has('is_featured'))    $data['is_featured']    = $request->input('is_featured')    === '1';
        if ($request->has('in_hero_slider')) $data['in_hero_slider'] = $request->input('in_hero_slider') === '1';

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('images/products', 'public');
            $data['main_image'] = 'storage/' . $path;
        }

        // Handle gallery images
        $existingGallery = $product->gallery_images ?? [];
        $retainedGallery = json_decode($request->input('retained_gallery_images', '[]'), true) ?? [];
        $finalGallery = array_values(array_intersect($existingGallery, $retainedGallery));

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('images/products', 'public');
                $finalGallery[] = 'storage/' . $path;
            }
        }
        $data['gallery_images'] = $finalGallery;

        $product->update($data);

        // Sync variants (JSON string from FormData)
        $variants = json_decode($request->input('variants', '[]'), true) ?? [];
        if (!empty($variants)) {
            $product->variants()->delete();
            foreach ($variants as $v) {
                if (empty($v['size'])) continue;
                $product->variants()->create([
                    'size'      => $v['size'],
                    'price'     => $v['price'] ?? $product->base_price,
                    'stock'     => $v['stock'] ?? 0,
                    'is_active' => true,
                ]);
            }
        }

        return response()->json($this->formatProduct($product->fresh(['category', 'variants'])));
    }


    /** DELETE /api/admin/products/{id} */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Product deleted.']);
    }

    /** Normalize product for API response */
    private function formatProduct($product): array
    {
        $imageUrl = $product->main_image
            ? url($product->main_image)
            : null;

        $galleryUrls = [];
        if (is_array($product->gallery_images)) {
            foreach ($product->gallery_images as $path) {
                $galleryUrls[] = [
                    'path' => $path,
                    'url' => url($path)
                ];
            }
        }

        return [
            'id'                => $product->id,
            'name'              => $product->name,
            'slug'              => $product->slug,
            'category_id'       => $product->category_id,
            'category_name'     => $product->category?->name ?? 'Uncategorized',
            'category_slug'     => $product->category?->slug ?? '',
            'short_description' => $product->short_description,
            'description'       => $product->description,
            'base_price'        => (float) $product->base_price,
            'stock'             => $product->stock,
            'main_image'        => $imageUrl,
            'gallery_images'    => $galleryUrls,
            'fabric'            => $product->fabric,
            'care_instructions' => $product->care_instructions,
            'is_active'         => (bool) $product->is_active,
            'is_featured'       => (bool) $product->is_featured,
            'in_hero_slider'    => (bool) $product->in_hero_slider,
            'sort_order'        => $product->sort_order,
            'variants'          => $product->variants->map(fn($v) => [
                'id'        => $v->id,
                'size'      => $v->size,
                'price'     => (float) $v->price,
                'stock'     => $v->stock,
                'is_active' => (bool) $v->is_active,
            ])->values()->toArray(),
        ];
    }
}
