<?php

namespace Workdo\SmsCredit\Helpers;

use Workdo\SmsCredit\Entities\SmsCreditBalance;

class SmsCreditHelper
{
    /**
     * Check if user has sufficient credits
     */
    public static function hasCredits($requiredCredits, $clientId = null, $workspace = null)
    {
        $clientId = $clientId ?? creatorId();
        $workspace = $workspace ?? getActiveWorkSpace();

        $balance = SmsCreditBalance::getBalance($clientId, $workspace);
        return $balance->remaining_credits >= $requiredCredits;
    }

    /**
     * Use credits for SMS
     */
    public static function useCredits($credits, $description = null, $clientId = null, $workspace = null)
    {
        $clientId = $clientId ?? creatorId();
        $workspace = $workspace ?? getActiveWorkSpace();

        $balance = SmsCreditBalance::getBalance($clientId, $workspace);
        return $balance->useCredits($credits, $description);
    }

    /**
     * Get current balance
     */
    public static function getBalance($clientId = null, $workspace = null)
    {
        $clientId = $clientId ?? creatorId();
        $workspace = $workspace ?? getActiveWorkSpace();

        $balance = SmsCreditBalance::getBalance($clientId, $workspace);
        return $balance->remaining_credits;
    }

    /**
     * Calculate credits needed for SMS
     */
    public static function calculateCreditsNeeded($messageLength)
    {
        // First 150 characters = 1 credit
        // Every additional 100 characters = 1 credit
        if ($messageLength <= 150) {
            return 1;
        }

        return 1 + ceil(($messageLength - 150) / 100);
    }

    /**
     * Get credits needed for bulk SMS
     */
    public static function calculateBulkCredits($recipientCount, $messageLength)
    {
        $creditsPerMessage = self::calculateCreditsNeeded($messageLength);
        return $recipientCount * $creditsPerMessage;
    }
}
