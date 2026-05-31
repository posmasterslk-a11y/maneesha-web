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
            'image' => 'required|image|max:5120', // Max 5MB
        ]);

        $path = $request->file('image')->store('hero_slides', 'public');

        // Put new slide at the end
        $maxOrder = HeroSlide::max('order_index');
        
        $slide = HeroSlide::create([
            'image_path' => $path,
            'order_index' => $maxOrder !== null ? $maxOrder + 1 : 0,
            'is_active' => true,
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
