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
        $setting = Setting::firstOrCreate(
            ['key' => 'sms_enabled'],
            ['value' => '0']
        );
        return response()->json(['sms_enabled' => $setting->value === '1']);
    }

    public function updateSettings(Request $request)
    {
        $request->validate(['sms_enabled' => 'required|boolean']);
        
        Setting::updateOrCreate(
            ['key' => 'sms_enabled'],
            ['value' => $request->sms_enabled ? '1' : '0']
        );

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
