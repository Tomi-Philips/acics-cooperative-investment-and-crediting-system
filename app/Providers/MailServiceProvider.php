<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\MailManager;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Create a mail preview directory if it doesn't exist
        if (!file_exists(storage_path('mail-previews'))) {
            mkdir(storage_path('mail-previews'), 0755, true);
        }

        // Intercept emails when using the 'log' mail driver
        Mail::extend('log', function (array $config, array $extensions, $transporter = null) {
            return new Mailer(
                'log',
                app('view'),
                $transporter ?? new \Symfony\Component\Mailer\Transport\NullTransport(),
                app('events')
            );
        });

        // Listen for sent messages
        // Mail::getSwiftMailer()->registerPlugin(new \App\Services\MailPreviewPlugin());
    }
}