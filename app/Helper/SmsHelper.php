<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkSpace;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    /**
     * Send SMS via MNotify Quick SMS API
     *
     * @param string|array $recipient Single number or array of numbers (e.g., '233240123456' or ['233240123456', '233501234567'])
     * @param string $message
     * @param string|null $senderId Optional sender ID, defaults to env or workspace sender
     * @return array
     */
    public static function sendSms($recipient, $message, $senderId = null)
    {
        $apiKey = env('MNOTIFY_API_KEY');
        $baseUrl = env('MNOTIFY_BASE_URL', 'https://api.mnotify.com/api/sms/quick');

        if (empty($apiKey)) {
            return ['status' => false, 'message' => 'MNotify API key not configured'];
        }

        // Step 1: Determine sender ID
        if (Auth::check()) {
            $user = Auth::user();
            $workspace = WorkSpace::find($user->active_workspace ?? null);
            if ($workspace && !empty($workspace->sms_sender_id)) {
                $senderId = $workspace->sms_sender_id;
            }
        }

        if (empty($senderId)) {
            $senderId = env('DEFAULT_SMS_SENDER', 'BizApp');
        }

        // Step 2: Convert recipient to array if string
        $recipients = is_array($recipient) ? $recipient : [$recipient];

        // Step 3: Prepare JSON payload
        $payload = [
            'key' => $apiKey,
            'recipient' => $recipients,
            'sender' => $senderId,
            'message' => $message,
            'is_schedule' => false,
            'schedule_date' => '', // Required by API even if false
        ];

        // Step 4: Log payload for debugging
        Log::info('MNotify SMS Payload', $payload);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($baseUrl, $payload);

            $data = $response->json();

            // Step 5: Check for success
            if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
                return [
                    'status' => true,
                    'message' => 'SMS sent successfully',
                    'data' => $data,
                    'senderId' => $senderId,
                ];
            }

            // Return API error message
            return [
                'status' => false,
                'message' => $data['message'] ?? 'MNotify API error',
                'data' => $data,
            ];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'SMS sending failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Count SMS pages (for billing purposes)
     *
     * @param string $message
     * @return int
     */
    public static function countSmsPages($message)
    {
        $length = strlen($message);
        if ($length <= 150) return 1;
        $extra = ceil(($length - 150) / 100);
        return 1 + $extra;
    }
}
