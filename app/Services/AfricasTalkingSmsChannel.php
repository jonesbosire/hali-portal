<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Custom Laravel notification channel for Africa's Talking SMS.
 *
 * Usage in a Notification class:
 *   public function via($notifiable): array { return ['sms']; }
 *   public function toSms($notifiable): string { return "Your message here."; }
 *
 * Required .env keys:
 *   AT_USERNAME  — Africa's Talking username (use "sandbox" for testing)
 *   AT_API_KEY   — Africa's Talking API key
 *   AT_FROM      — Sender ID (optional; leave blank to use shortcode)
 *
 * The notifiable (User) must have a non-empty `phone` attribute.
 */
class AfricasTalkingSmsChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        // Skip if the notification doesn't define an SMS message
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $phone = $this->resolvePhone($notifiable);
        if (!$phone) {
            Log::info('SMS skipped — no phone number for user.', [
                'user_id' => $notifiable->id ?? null,
            ]);
            return;
        }

        $message = $notification->toSms($notifiable);

        $username = config('services.africastalking.username');
        $apiKey   = config('services.africastalking.api_key');
        $from     = config('services.africastalking.from') ?: null;

        if (!$username || !$apiKey) {
            Log::warning('Africa\'s Talking credentials not configured. Set AT_USERNAME and AT_API_KEY in .env');
            return;
        }

        try {
            $AT  = new AfricasTalking($username, $apiKey);
            $sms = $AT->sms();

            $params = [
                'to'      => $phone,
                'message' => $message,
            ];
            if ($from) {
                $params['from'] = $from;
            }

            $result = $sms->send($params);

            Log::info('SMS sent via Africa\'s Talking.', [
                'to'     => $phone,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            // Log and swallow — a failed SMS must not break the main flow
            Log::error('Africa\'s Talking SMS failed.', [
                'to'    => $phone,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function resolvePhone(mixed $notifiable): ?string
    {
        $phone = $notifiable->phone ?? null;
        if (!$phone) return null;

        // Strip non-digit characters except leading +
        $clean = preg_replace('/[^\d+]/', '', $phone);

        // AT requires E.164 format (+254XXXXXXXXX)
        // If no country code prefix, assume Kenya (+254) — adjust as needed
        if (str_starts_with($clean, '0')) {
            $clean = '+254' . substr($clean, 1);
        } elseif (!str_starts_with($clean, '+')) {
            $clean = '+' . $clean;
        }

        return strlen($clean) >= 10 ? $clean : null;
    }
}
