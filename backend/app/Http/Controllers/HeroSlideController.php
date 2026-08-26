<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    /**
     * Get all active hero slides, ordered by index
     */
    public function index()
    {
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order_index', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Append full URL for image path
        $slides->transform(function ($slide) {
            $slide->image_url = asset('storage/' . $slide->image_path);
            return $slide;
        });

        return response()->json($slides);
    }

    /**
     * Admin: Get all slides (active or not)
     */
    public function adminIndex()
    {
        $slides = HeroSlide::orderBy('order_index', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $slides->transform(function ($slide) {
            $slide->image_url = asset('storage/' . $slide->image_path);
            return $slide;
        });

        return response()->json($slides);
    }

    /**
     * Upload a new slide
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
            'subtitle' => 'nullable|string|max:255',
            'title_top' => 'nullable|string|max:255',
            'title_bottom' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'btn_text' => 'nullable|string|max:255',
            'btn_link' => 'nullable|string|max:255',
            'show_text' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('hero_slides', 'public');

        // Put new slide at the end
        $maxOrder = HeroSlide::max('order_index');
        
        $slide = HeroSlide::create([
            'image_path' => $path,
            'order_index' => $maxOrder !== null ? $maxOrder + 1 : 0,
            'is_active' => true,
            'subtitle' => $request->subtitle,
            'title_top' => $request->title_top,
            'title_bottom' => $request->title_bottom,
            'desc' => $request->desc,
            'btn_text' => $request->btn_text,
            'btn_link' => $request->btn_link,
            'show_text' => $request->has('show_text') ? filter_var($request->show_text, FILTER_VALIDATE_BOOLEAN) : true,
        ]);

        $slide->image_url = asset('storage/' . $slide->image_path);

        return response()->json([
            'success' => true,
            'message' => 'Slide uploaded successfully.',
            'slide' => $slide
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        return response()->json([
            'success' => true,
            'message' => 'Slide status updated.',
            'is_active' => $slide->is_active
        ]);
    }

    /**
     * Update slide text/details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'subtitle' => 'nullable|string|max:255',
            'title_top' => 'nullable|string|max:255',
            'title_bottom' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'btn_text' => 'nullable|string|max:255',
            'btn_link' => 'nullable|string|max:255',
            'show_text' => 'nullable|boolean',
        ]);

        $slide = HeroSlide::findOrFail($id);
        
        $slide->update([
            'subtitle' => $request->subtitle,
            'title_top' => $request->title_top,
            'title_bottom' => $request->title_bottom,
            'desc' => $request->desc,
            'btn_text' => $request->btn_text,
            'btn_link' => $request->btn_link,
            'show_text' => $request->has('show_text') ? filter_var($request->show_text, FILTER_VALIDATE_BOOLEAN) : true,
        ]);

        $slide->image_url = asset('storage/' . $slide->image_path);

        return response()->json([
            'success' => true,
            'message' => 'Slide updated successfully.',
            'slide' => $slide
        ]);
    }

    /**
     * Delete a slide
     */
    public function destroy($id)
    {
        $slide = HeroSlide::findOrFail($id);
        
        // Delete image from storage
        if (Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slide deleted successfully.'
        ]);
    }
}
