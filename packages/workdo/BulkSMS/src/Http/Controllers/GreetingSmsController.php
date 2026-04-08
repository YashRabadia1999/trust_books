<?php

namespace Workdo\BulkSMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Workdo\SmsCredit\Services\SmsService;

class GreetingSmsController extends Controller
{
    /**
     * Display the greeting SMS form
     */
    public function index()
    {
        if (!Auth::user()->isAbleTo('bulksms manage')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        // Get users created by the company
        $users = User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->where('type', '!=', 'super admin')
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->select('id', 'name', 'email', 'mobile_no', 'type', 'is_disable')
            ->orderBy('name')
            ->get();

        return view('bulk-sms::greeting.index', compact('users'));
    }

    /**
     * Send greeting SMS to selected users
     */
    public function send(Request $request)
    {
        if (!Auth::user()->isAbleTo('bulksms manage')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        // Validate request
        $validator = \Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'message' => 'required|string|min:10|max:500',
            'greeting_type' => 'required|in:custom,seasonal,general',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        // Check if SmsCredit module is active
        // if (!module_is_active('SmsCredit')) {
        //     return redirect()->back()->with('error', __('SmsCredit module is not active. Please activate it to send SMS.'));
        // }

        // Check if Seasonal Greetings SMS notification is enabled
        $settings = getCompanyAllSetting();
        $notificationKey = $request->greeting_type === 'seasonal' ? 'Seasonal Greetings_sms' : 'Birthday Wishes_sms';

        if (!isset($settings[$notificationKey]) || $settings[$notificationKey] != '1') {
            return redirect()->back()->with('error', __('Greeting SMS notification is disabled. Please enable it in SMS settings.'));
        }

        $userIds = $request->user_ids;
        $message = $request->message;
        $greetingType = $request->greeting_type;

        // Get selected users
        $users = User::whereIn('id', $userIds)
            ->where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', __('No valid users selected.'));
        }

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                // Personalize message with user's name
                $personalizedMessage = $this->personalizeMessage($message, $user);

                // Send SMS using SmsService
                $result = SmsService::send(
                    $user->mobile_no,
                    $personalizedMessage,
                    creatorId(),
                    getActiveWorkSpace()
                );

                if ($result['success']) {
                    $successCount++;
                    Log::info("Greeting SMS sent to {$user->name} (ID: {$user->id})");
                } else {
                    $failCount++;
                    $errors[] = "{$user->name}: {$result['message']}";
                    Log::error("Greeting SMS failed for {$user->name} (ID: {$user->id}): {$result['message']}");
                }
            } catch (\Exception $e) {
                $failCount++;
                $errors[] = "{$user->name}: {$e->getMessage()}";
                Log::error("Greeting SMS error for {$user->name} (ID: {$user->id}): {$e->getMessage()}");
            }
        }

        // Prepare response message
        $message = __('SMS sending completed.');
        $message .= " " . __('Successful: :count', ['count' => $successCount]);

        if ($failCount > 0) {
            $message .= ", " . __('Failed: :count', ['count' => $failCount]);

            if (!empty($errors)) {
                $errorDetails = implode('; ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $errorDetails .= '...';
                }
                return redirect()->route('bulksms.greeting.index')
                    ->with('warning', $message . '. Errors: ' . $errorDetails);
            }
        }

        return redirect()->route('bulksms.greeting.index')
            ->with('success', $message);
    }

    /**
     * Personalize message with user data
     */
    private function personalizeMessage($message, $user)
    {
        $replacements = [
            '{name}' => $user->name,
            '{first_name}' => explode(' ', $user->name)[0],
            '{email}' => $user->email,
            '{company}' => getCompanyAllSetting()['company_name'] ?? admin_setting('title_text') ?? 'Our Company',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /**
     * Get predefined greeting templates
     */
    public function getTemplates(Request $request)
    {
        $type = $request->get('type', 'general');

        $templates = [
            'seasonal' => [
                [
                    'name' => 'New Year Greeting',
                    'message' => "🎊 Happy New Year, {name}!\n\nWishing you a year filled with success, happiness, and prosperity.\n\nBest wishes from {company}!"
                ],
                [
                    'name' => 'Christmas Greeting',
                    'message' => "🎄 Merry Christmas, {name}!\n\nMay this festive season bring you joy and wonderful moments.\n\nWarm wishes from {company}!"
                ],
                [
                    'name' => 'Easter Greeting',
                    'message' => "🐰 Happy Easter, {name}!\n\nWishing you a blessed Easter filled with hope and renewal.\n\nBest regards from {company}!"
                ],
            ],
            'general' => [
                [
                    'name' => 'Thank You Message',
                    'message' => "Dear {name},\n\nThank you for being a valued member of our community. We appreciate your continued support.\n\nBest regards,\n{company}"
                ],
                [
                    'name' => 'Welcome Message',
                    'message' => "Hello {first_name}! 👋\n\nWelcome to {company}! We're excited to have you with us.\n\nFeel free to reach out if you need any assistance."
                ],
                [
                    'name' => 'Appreciation Message',
                    'message' => "Dear {name},\n\nWe wanted to take a moment to appreciate your dedication and hard work.\n\nThank you for all you do!\n\n- {company} Team"
                ],
            ],
            'custom' => [
                [
                    'name' => 'Custom Message',
                    'message' => "Hello {name},\n\n[Your custom message here]\n\nBest regards,\n{company}"
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'templates' => $templates[$type] ?? $templates['general']
        ]);
    }
}
