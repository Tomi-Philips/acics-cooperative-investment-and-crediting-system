<?php

namespace App\Services;

use Swift_Events_SendEvent;
use Swift_Events_SendListener;
use Illuminate\Support\Str;

class MailPreviewPlugin implements Swift_Events_SendListener
{
    /**
     * Handle the Swift_Events_SendEvent
     * Save email content to a file that can be viewed in the browser
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $message = $evt->getMessage();
        $to = array_keys($message->getTo())[0];
        $subject = $message->getSubject();

        // Create a filename based on the email details
        $filename = date('Y-m-d_H-i-s') . '_' . Str::slug(substr($subject, 0, 30)) . '_' . Str::slug(substr($to, 0, 20)) . '.html';
        $path = storage_path('mail-previews/' . $filename);

        // Get the email HTML body
        $body = $message->getBody();

        // Save the email to a file
        file_put_contents($path, $body);

        // Log file location for easy access
        \Illuminate\Support\Facades\Log::info('Mail preview saved', [
            'path' => $path,
            'to' => $to,
            'subject' => $subject
        ]);
    }

    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        // This method is required by the interface but we don't need to do anything here
    }
}