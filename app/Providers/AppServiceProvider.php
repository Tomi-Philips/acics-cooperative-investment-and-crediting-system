<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending memberships count and pending loans count with all views
        View::composer('layouts.admin', function ($view) {
            $pendingMembershipsCount = User::where('status', 'pending')->count();
            $pendingLoansCount = Loan::where('status', 'pending')->count();

            $view->with([
                'pendingMembershipsCount' => $pendingMembershipsCount,
                'pendingLoansCount' => $pendingLoansCount
            ]);
        });
        
        // Create mail-previews directory if it doesn't exist
        $previewDir = storage_path('mail-previews');
        if (!File::exists($previewDir)) {
            File::makeDirectory($previewDir, 0755, true);
        }
        
        // When using log driver, save the email content to a file
        \Illuminate\Support\Facades\Event::listen(MessageSending::class, function (MessageSending $event) {
            $message = $event->message;
            $to = array_keys($message->getTo())[0] ?? 'unknown';
            $subject = $message->getSubject() ?? 'No Subject';
            
            $filename = date('Y-m-d_H-i-s') . '_' . Str::slug(substr($subject, 0, 30)) . '_' . Str::slug(substr($to, 0, 20)) . '.html';
            $path = storage_path('mail-previews/' . $filename);
            
            // Get email content
            $body = $message->getHtmlBody() ?? $message->getBody();
            
            // Save to file
            file_put_contents($path, $body);
            
            // Log the path
            \Illuminate\Support\Facades\Log::info('Email preview saved', [
                'path' => $path,
                'subject' => $subject,
                'to' => $to
            ]);
        });
    }
}
