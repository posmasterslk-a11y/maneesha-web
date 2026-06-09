<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Get delivery charges from settings
     */
    public function getDeliveryCharges()
    {
        $setting = Setting::where('key', 'delivery_charges')->first();
        
        $charges = $setting ? json_decode($setting->value, true) : [];
        
        // Default districts and 300 LKR base charge if empty
        $districts = [
            'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
            'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
            'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
            'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
            'Moneragala', 'Ratnapura', 'Kegalle'
        ];

        $defaultCharges = [];
        foreach ($districts as $district) {
            $defaultCharges[$district] = isset($charges[$district]) ? $charges[$district] : 300;
        }

        return response()->json($defaultCharges);
    }

    /**
     * Save delivery charges from admin panel
     */
    public function saveDeliveryCharges(Request $request)
    {
        // Require admin access, assuming standard user auth middleware applies
        if ($request->user() && $request->user()->role !== 'admin' && $request->user()->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $charges = $request->input('charges', []);

        $setting = Setting::firstOrCreate(['key' => 'delivery_charges']);
        $setting->value = json_encode($charges);
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Delivery charges saved successfully',
            'data' => $charges
        ]);
    }
}
