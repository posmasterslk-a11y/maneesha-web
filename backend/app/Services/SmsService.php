<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiUrl;
    protected string $apiToken;
    protected string $senderId;

    public function __construct()
    {
        // Default credentials as supplied by user, with fallback config support
        $this->apiUrl = env('SMS_API_URL', 'https://dashboard.smsapi.lk/api/v3/sendsms');
        $this->apiToken = env('SMS_API_TOKEN', '172|jbMj5WdmlWftzoxtTr64n9pevmBlPosoIYARLlU5');
        $this->senderId = env('SMS_SENDER_ID', 'POS Masters');
    }

    /**
     * Dispatch tailored SMS to Sri Lankan mobile number
     *
     * @param string $recipient Mobile number e.g. 0771234567 or 94771234567
     * @param string $message Alphanumeric SMS body content
     * @return bool
     */
    public function sendSms(string $recipient, string $message): bool
    {
        // Sanitize and format recipient number to standard international structure (947XXXXXXXX)
        $formattedRecipient = $this->formatNumber($recipient);

        try {
            Log::info("Initiating SMS dispatch to {$formattedRecipient} via smsapi.lk...");

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiToken}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'recipient' => $formattedRecipient,
                'sender_id' => $this->senderId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("SMS successfully delivered to {$formattedRecipient} via smsapi.lk. Response: " . $response->body());
                return true;
            }

            Log::error("SMS dispatch failed for {$formattedRecipient}. Status Code: " . $response->status() . " | Error: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Critical Exception during SMS dispatch to {$formattedRecipient}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper to format numbers matching Sri Lankan provider norms (947XXXXXXXX)
     */
    protected function formatNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert starting "0" or prefix strings
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '94' . substr($cleaned, 1);
        }
        
        if (!str_starts_with($cleaned, '94')) {
            $cleaned = '94' . $cleaned;
        }

        return $cleaned;
    }
}
