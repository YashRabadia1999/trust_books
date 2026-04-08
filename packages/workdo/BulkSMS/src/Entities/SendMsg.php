<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkSpace;

class SendMsg
{
    /**
     * Send SMS via Mnotify API (quick SMS)
     *
     * @param string|array $recipient e.g. "233XXXXXXXXX" or [["233XXXXXXXXX"]]
     * @param string $message
     * @param string|null $senderId
     * @return array
     */
    public static function sendSms($recipient, $message, $senderId = null)
    {
        $apiKey = env('MNOTIFY_API_KEY');
        $baseUrl = 'https://api.mnotify.com/api/sms/quick';

        if (empty($apiKey)) {
            return ['status' => false, 'message' => 'Mnotify API key not configured'];
        }

        // Step 1: Get workspace sender ID
        if (Auth::check()) {
            $user = Auth::user();
            $workspace = WorkSpace::find($user->active_workspace ?? null);

            if ($workspace && !empty($workspace->sms_sender_id)) {
                $senderId = $workspace->sms_sender_id;
            }
        }

        // Step 2: Default sender fallback
        if (empty($senderId)) {
            $senderId = env('DEFAULT_SMS_SENDER', 'BizApp');
        }

        // Step 3: Test mode
        if (env('APP_ENV') === 'local' || env('MNOTIFY_API_KEY') === 'TEST12345') {
            return [
                'status' => true,
                'message' => 'Test mode: SMS not actually sent',
                'senderId' => $senderId,
            ];
        }

        // Step 4: Format recipient as array if string provided
        if (is_string($recipient)) {
            $recipient = [$recipient];
        }

        // Step 5: Prepare payload (recipient should be array of strings)
        $payload = [
            'key' => $apiKey,
            'recipient' => $recipient,
            'sender' => substr($senderId, 0, 11), // max 11 chars
            'message' => $message
            // 'is_schedule' => false
        ];

        try {
            $response = Http::asForm()->post($baseUrl, $payload);
            $data = $response->json();
            // dd($data);

            if ($response->successful()) {
                if (isset($data['code']) && $data['code'] == '1004') {
                    return [
                        'status' => false,
                        'message' => 'Invalid API key. Please verify your MNotify key.',
                        'data' => $data,
                    ];
                }

                return [
                    'status' => true,
                    'message' => $data['message'] ?? 'SMS sent successfully',
                    'data' => $data,
                    'senderId' => $senderId,
                ];
            }

            return [
                'status' => false,
                'message' => 'Mnotify API request failed',
                'data' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'SMS sending failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS (wrapper for compatibility with controller)
     *
     * @param string $mobile Mobile number(s)
     * @param array $uArr User array containing sender_id
     * @param string $message SMS message
     * @return array
     */
    public static function SendMsgs($mobile, $uArr, $message)
    {
        $senderId = $uArr['sender_id'] ?? null;

        // Handle multiple numbers (comma-separated)
        $numbers = is_array($mobile) ? $mobile : explode(',', $mobile);
        $results = [];
        $hasError = false;

        foreach ($numbers as $number) {
            $number = trim($number);
            if (empty($number)) {
                continue;
            }

            $result = self::sendSms($mobile, $message, $senderId);
            // dd($result);
            $results[] = $result;

            if (!$result['status']) {
                $hasError = true;
            }
        }

        // Return format expected by controller
        if (count($results) === 1) {
            return [
                'error' => !$results[0]['status'],
                'response' => [
                    'status' => $results[0]['status'] ? 'sent' : 'failed',
                    'message' => $results[0]['message'] ?? '',
                ]
            ];
        }

        return [
            'error' => $hasError,
            'response' => [
                'status' => $hasError ? 'partially_sent' : 'sent',
                'message' => count($results) . ' message(s) processed',
                'results' => $results
            ]
        ];
    }
}
