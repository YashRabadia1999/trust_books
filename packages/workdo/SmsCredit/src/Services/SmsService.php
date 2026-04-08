<?php

namespace Workdo\SmsCredit\Services;

use Workdo\SmsCredit\Helpers\SmsCreditHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS message
     */
    public static function send($mobileNumber, $message, $clientId = null, $workspace = null)
    {
        $clientId = $clientId ?? creatorId();
        $workspace = $workspace ?? getActiveWorkSpace();

        // Calculate credits needed
        $creditsNeeded = SmsCreditHelper::calculateCreditsNeeded(strlen($message));

        // Check if user has sufficient credits
        if (!SmsCreditHelper::hasCredits($creditsNeeded, $clientId, $workspace)) {
            return [
                'success' => false,
                'message' => 'Insufficient SMS credits. Required: ' . $creditsNeeded
            ];
        }

        // Format mobile number
        $mobileNumber = self::formatMobileNumber($mobileNumber);

        try {
            // Send SMS via MNotify
            $response = self::sendViaMNotify($mobileNumber, $message);

            if ($response['success']) {
                // Deduct credits
                SmsCreditHelper::useCredits(
                    $creditsNeeded,
                    "SMS sent to {$mobileNumber}",
                    $clientId,
                    $workspace
                );

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'credits_used' => $creditsNeeded
                ];
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('SMS Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send via MNotify API
     */
    private static function sendViaMNotify($mobileNumber, $message)
    {
        $apiKey = env('MNOTIFY_API_KEY');
        $senderId = env('DEFAULT_SMS_SENDER', 'BizApp');

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'MNotify API key not configured'
            ];
        }

        // Test mode
        if (env('APP_ENV') === 'local' || $apiKey === 'TEST12345') {
            return [
                'success' => true,
                'message' => 'Test mode: SMS not actually sent',
                'senderId' => $senderId,
            ];
        }

        try {
            $response = Http::asForm()->post('https://api.mnotify.com/api/sms/quick', [
                'key' => $apiKey,
                'recipient' => [$mobileNumber],
                'sender' => substr($senderId, 0, 11), // max 11 chars
                'message' => $message
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['code']) && $data['code'] == '1004') {
                    return [
                        'success' => false,
                        'message' => 'Invalid API key. Please verify your MNotify key.',
                    ];
                }

                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'SMS sent successfully',
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'MNotify API request failed: ' . $response->body()
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Format mobile number to international format
     */
    private static function formatMobileNumber($number)
    {
        // Remove spaces and special characters
        $number = preg_replace('/[^0-9+]/', '', $number);

        // Convert to +233 format
        if (substr($number, 0, 1) == '0') {
            return '+233' . substr($number, 1);
        } elseif (substr($number, 0, 3) == '233') {
            return '+' . $number;
        } elseif (substr($number, 0, 1) != '+') {
            return '+233' . $number;
        }

        return $number;
    }

    /**
     * Send invoice created SMS
     */
    public static function sendInvoiceCreatedSms($invoice, $customer)
    {
        $mobileNumber = $customer->mobile_no ?? $customer->billing_phone ?? null;

        if (empty($mobileNumber)) {
            return [
                'success' => false,
                'message' => 'Customer mobile number not found'
            ];
        }

        $companyName = getCompanyAllSetting()['company_name'] ?? 'Our Company';

        $message = "Dear {$customer->name},\n\n";
        $message .= "Invoice #{$invoice->invoice_id} has been created.\n";
        $message .= "Amount: GHS " . number_format($invoice->getTotal(), 2) . "\n";
        $message .= "Due Date: " . date('d/m/Y', strtotime($invoice->due_date)) . "\n\n";
        $message .= "Thank you for your business!\n";
        $message .= "- {$companyName}";

        return self::send($mobileNumber, $message, $invoice->created_by, $invoice->workspace);
    }

    /**
     * Send payment received SMS
     */
    public static function sendPaymentReceivedSms($invoice, $payment, $customer)
    {
        $mobileNumber = $customer->mobile_no ?? $customer->billing_phone ?? null;

        if (empty($mobileNumber)) {
            return [
                'success' => false,
                'message' => 'Customer mobile number not found'
            ];
        }

        $companyName = getCompanyAllSetting()['company_name'] ?? 'Our Company';
        $totalInvoice = $invoice->getTotal();

        // Calculate total paid from all payments
        $totalPaid = 0;
        foreach ($invoice->payments as $p) {
            $totalPaid += $p->amount;
        }

        $totalDue = $invoice->getDue();
        $paidNow = $payment->amount;

        $message = "Dear {$customer->name},\n\n";
        $message .= "Payment received for Invoice #{$invoice->invoice_id}\n\n";
        $message .= "Invoice Amount: GHS " . number_format($totalInvoice, 2) . "\n";
        $message .= "Paid Now: GHS " . number_format($paidNow, 2) . "\n";
        $message .= "Total Paid: GHS " . number_format($totalPaid, 2) . "\n";
        $message .= "Balance Due: GHS " . number_format($totalDue, 2) . "\n\n";
        $message .= "Thank you for your payment!\n";
        $message .= "- {$companyName}";

        return self::send($mobileNumber, $message, $invoice->created_by, $invoice->workspace);
    }
}
