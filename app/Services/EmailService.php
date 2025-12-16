<?php

namespace App\Services;

use App\Mail\GeneralNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send a general notification email.
     *
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string|array $content Main content body
     * @param array $data Additional data for the view
     * @param string|null $viewName Custom view name
     * @return bool True if queued/sent successfully, False otherwise
     */
    public static function send($to, $subject, $content, $data = [], $viewName = null)
    {
        try {
            Mail::to($to)->send(new GeneralNotificationMail($subject, $content, $data, $viewName));
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to " . (is_array($to) ? json_encode($to) : $to) . ": " . $e->getMessage());
            return false;
        }
    }
}
