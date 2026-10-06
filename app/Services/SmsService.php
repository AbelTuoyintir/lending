<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS notification to the given phone number.
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        // Log the SMS notification for audit trailing and simulation
        Log::info("SMS notification dispatched to {$phoneNumber}: {$message}");

        return true;
    }
}
