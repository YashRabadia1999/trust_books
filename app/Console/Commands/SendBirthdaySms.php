<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Workdo\SmsCredit\Services\SmsService;

class SendBirthdaySms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:send-sms {--include-disabled : Include disabled users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday SMS greetings to users whose birthday is today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting birthday SMS sending process...');

        // Check if SmsCredit module is active
        if (!module_is_active('SmsCredit')) {
            $this->error('SmsCredit module is not active. Birthday SMS cannot be sent.');
            Log::warning('Birthday SMS: SmsCredit module is not active');
            return Command::FAILURE;
        }

        // Get today's date (month and day only)
        $today = now();
        $todayMonth = $today->format('m');
        $todayDay = $today->format('d');

        $this->info("Looking for birthdays on: Month={$todayMonth}, Day={$todayDay}");

        // Find users whose birthday is today
        $query = User::whereNotNull('birthday')
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->whereRaw('MONTH(birthday) = ?', [$todayMonth])
            ->whereRaw('DAY(birthday) = ?', [$todayDay]);

        // Only include active users unless --include-disabled option is used
        if (!$this->option('include-disabled')) {
            $query->whereIn('is_disable', [0, '0']);
        }

        $birthdayUsers = $query->get();

        $this->info("Query executed. Found: " . $birthdayUsers->count() . " users");

        if ($birthdayUsers->isEmpty()) {
            $this->info('No users with birthdays today.');
            return Command::SUCCESS;
        }

        $this->info('Found ' . $birthdayUsers->count() . ' users with birthdays today.');

        $successCount = 0;
        $failCount = 0;

        foreach ($birthdayUsers as $user) {
            try {
                // Check if Birthday Wishes SMS notification is enabled for this user's workspace
                $settings = getCompanyAllSetting($user->created_by ?: $user->id, $user->workspace_id);

                if (!isset($settings['Birthday Wishes_sms']) || $settings['Birthday Wishes_sms'] != '1') {
                    $this->warn("⊘ Birthday SMS disabled for {$user->name}'s workspace");
                    Log::info("Birthday SMS skipped for {$user->name} (ID: {$user->id}) - notification disabled");
                    continue;
                }

                // Prepare birthday message
                $message = $this->prepareBirthdayMessage($user);

                // Send SMS using SmsService
                $result = SmsService::send(
                    $user->mobile_no,
                    $message,
                    $user->created_by ?: $user->id,
                    $user->workspace_id ?: getActiveWorkSpace()
                );

                if ($result['success']) {
                    $successCount++;
                    $this->info("✓ Birthday SMS sent to {$user->name} ({$user->mobile_no})");
                    Log::info("Birthday SMS sent to {$user->name} (ID: {$user->id})");
                } else {
                    $failCount++;
                    $this->error("✗ Failed to send SMS to {$user->name}: {$result['message']}");
                    Log::error("Birthday SMS failed for {$user->name} (ID: {$user->id}): {$result['message']}");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->error("✗ Error sending SMS to {$user->name}: {$e->getMessage()}");
                Log::error("Birthday SMS error for {$user->name} (ID: {$user->id}): {$e->getMessage()}");
            }
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info("Birthday SMS Summary:");
        $this->info("Total Users: " . $birthdayUsers->count());
        $this->info("Successfully Sent: {$successCount}");
        $this->info("Failed: {$failCount}");
        $this->info(str_repeat('=', 50));

        Log::info("Birthday SMS cron completed. Success: {$successCount}, Failed: {$failCount}");

        return Command::SUCCESS;
    }

    /**
     * Prepare birthday greeting message
     *
     * @param User $user
     * @return string
     */
    private function prepareBirthdayMessage(User $user)
    {
        $companySettings = getCompanyAllSetting($user->created_by ?: $user->id, $user->workspace_id);
        $companyName = $companySettings['company_name'] ?? admin_setting('title_text') ?? 'Our Team';

        // Calculate age if birth year is available
        $age = '';
        if ($user->birthday) {
            $birthYear = date('Y', strtotime($user->birthday));
            $currentYear = date('Y');
            if ($birthYear > 1900 && $birthYear < $currentYear) {
                $calculatedAge = $currentYear - $birthYear;
                $age = "\nWishing you a wonderful {$calculatedAge}th birthday!\n";
            }
        }

        $message = "🎉 Happy Birthday, {$user->name}! 🎂\n\n";
        $message .= $age;
        $message .= "May this special day bring you joy, happiness, and all the wonderful things you deserve.\n\n";
        $message .= "Best wishes from all of us at {$companyName}!";

        return $message;
    }
}
