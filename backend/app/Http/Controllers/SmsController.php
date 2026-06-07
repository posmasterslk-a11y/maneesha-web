<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Setting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function getSettings()
    {
        $keys = [
            'sms_enabled' => '0',
            'sms_template_order_customer' => 'Hi {name}, your order #{order_id} has been placed successfully.',
            'sms_template_order_status' => 'Hi {name}, your order #{order_id} is now {status}.',
            'sms_template_order_admin' => 'New Order #{order_id} placed by {name} for LKR {total}.',
            'sms_admin_numbers' => ''
        ];

        $settings = [];
        foreach ($keys as $key => $default) {
            $setting = Setting::firstOrCreate(['key' => $key], ['value' => $default]);
            $settings[$key] = $setting->value;
        }

        return response()->json([
            'sms_enabled' => $settings['sms_enabled'] === '1',
            'sms_template_order_customer' => $settings['sms_template_order_customer'],
            'sms_template_order_status' => $settings['sms_template_order_status'],
            'sms_template_order_admin' => $settings['sms_template_order_admin'],
            'sms_admin_numbers' => $settings['sms_admin_numbers'],
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'sms_enabled' => 'nullable|boolean',
            'sms_template_order_customer' => 'nullable|string',
            'sms_template_order_status' => 'nullable|string',
            'sms_template_order_admin' => 'nullable|string',
            'sms_admin_numbers' => 'nullable|string',
        ]);
        
        if ($request->has('sms_enabled')) {
            Setting::updateOrCreate(['key' => 'sms_enabled'], ['value' => $request->sms_enabled ? '1' : '0']);
        }
        if ($request->has('sms_template_order_customer')) {
            Setting::updateOrCreate(['key' => 'sms_template_order_customer'], ['value' => $request->sms_template_order_customer ?? '']);
        }
        if ($request->has('sms_template_order_status')) {
            Setting::updateOrCreate(['key' => 'sms_template_order_status'], ['value' => $request->sms_template_order_status ?? '']);
        }
        if ($request->has('sms_template_order_admin')) {
            Setting::updateOrCreate(['key' => 'sms_template_order_admin'], ['value' => $request->sms_template_order_admin ?? '']);
        }
        if ($request->has('sms_admin_numbers')) {
            Setting::updateOrCreate(['key' => 'sms_admin_numbers'], ['value' => $request->sms_admin_numbers ?? '']);
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function getLogs()
    {
        $logs = SmsLog::orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }

    public function getBillingSummary()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $logsThisMonth = SmsLog::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();

        $totalSent = $logsThisMonth->where('status', 'sent')->count();
        $totalCost = $logsThisMonth->sum('cost');

        return response()->json([
            'total_sent' => $totalSent,
            'total_cost' => $totalCost,
            'month' => now()->format('F Y'),
        ]);
    }

    public function sendPromotional(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160'
        ]);

        // Get distinct phone numbers from orders
        $phones = DB::table('orders')->select('phone')->distinct()->pluck('phone');
        
        $sentCount = 0;
        foreach ($phones as $phone) {
            if ($phone && strlen($phone) >= 9) {
                // Dispatch could be queued, but we send synchronously for simplicity
                $success = $this->smsService->sendSms($phone, $request->message, 'promotional');
                if ($success) {
                    $sentCount++;
                }
            }
        }

        return response()->json([
            'message' => 'Promotional SMS dispatch completed',
            'total_recipients' => $phones->count(),
            'total_sent' => $sentCount
        ]);
    }
}
