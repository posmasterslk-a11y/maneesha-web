<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Check if SMS is enabled globally
     */
    public function isEnabled(): bool
    {
        $setting = Setting::where('key', 'sms_enabled')->first();
        return $setting ? $setting->value === '1' : false;
    }

    /**
     * Send an SMS via smsapi.lk API v3
     */
    public function sendSms(string $phone, string $message, string $type = 'transactional'): bool
    {
        if (!$this->isEnabled()) {
            Log::info("SMS sending disabled. Did not send SMS to: {$phone}");
            return false;
        }

        $token = env('SMS_API_TOKEN');
        $senderId = env('SMS_SENDER_ID', 'POS Masters');
        
        if (!$token) {
            Log::error('SMS API Token is missing in environment variables.');
            return false;
        }

        // Format phone number to start with 94 if needed
        $formattedPhone = $phone;
        if (str_starts_with($formattedPhone, '0')) {
            $formattedPhone = '94' . substr($formattedPhone, 1);
        }

        try {
            // According to standard smsapi.lk v3 usage
            $response = Http::withToken($token)
                ->post('https://dashboard.smsapi.lk/api/v3/sms/send', [
                    'recipient' => $formattedPhone,
                    'sender_id' => $senderId,
                    'message' => $message,
                ]);

            $status = $response->successful() ? 'sent' : 'failed';

            SmsLog::create([
                'phone' => $formattedPhone,
                'message' => $message,
                'type' => $type,
                'status' => $status,
                'cost' => 1.30,
            ]);

            if (!$response->successful()) {
                Log::error("SMS API Error: " . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("SMS Exception: " . $e->getMessage());
            
            SmsLog::create([
                'phone' => $formattedPhone,
                'message' => $message,
                'type' => $type,
                'status' => 'failed',
                'cost' => 1.30,
            ]);
            
            return false;
        }
    }

    /**
     * Parse template by replacing placeholders with data
     */
    public function parseTemplate(string $templateKey, array $data): ?string
    {
        $setting = Setting::where('key', $templateKey)->first();
        if (!$setting || empty($setting->value)) {
            return null;
        }

        $message = $setting->value;
        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Send order placed SMS to customer
     */
    public function sendOrderPlacedCustomer(string $phone, array $data): bool
    {
        $message = $this->parseTemplate('sms_template_order_customer', $data);
        if ($message) {
            return $this->sendSms($phone, $message, 'transactional');
        }
        return false;
    }

    /**
     * Send order status update SMS to customer
     */
    public function sendOrderStatusCustomer(string $phone, array $data): bool
    {
        $message = $this->parseTemplate('sms_template_order_status', $data);
        if ($message) {
            return $this->sendSms($phone, $message, 'transactional');
        }
        return false;
    }

    /**
     * Send order placed SMS to admins
     */
    public function sendOrderPlacedAdmin(array $data): void
    {
        $adminNumbersSetting = Setting::where('key', 'sms_admin_numbers')->first();
        if (!$adminNumbersSetting || empty($adminNumbersSetting->value)) {
            return;
        }

        $message = $this->parseTemplate('sms_template_order_admin', $data);
        if (!$message) {
            return;
        }

        $numbers = explode(',', $adminNumbersSetting->value);
        foreach ($numbers as $number) {
            $number = trim($number);
            if (!empty($number)) {
                $this->sendSms($number, $message, 'admin_notification');
            }
        }
    }
}
