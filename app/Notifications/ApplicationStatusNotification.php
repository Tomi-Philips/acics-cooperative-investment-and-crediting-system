<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $applicationData;

    /**
     * Create a new notification instance.
     *
     * @param string $type The type of notification (submitted, verified, approved, rejected)
     * @param array $applicationData Application data including reference number
     */
    public function __construct($type, $applicationData)
    {
        $this->type = $type;
        $this->applicationData = $applicationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting($this->getGreeting());
        
        // Add appropriate message based on notification type
        switch ($this->type) {
            case 'submitted':
                $mail->line('Thank you for submitting your membership application with ACICS.')
                    ->line('Your application has been received and is pending verification.')
                    ->line('Please visit our office within 14 days with your supporting documents to continue the verification process.')
                    ->line('Your application reference number is: **' . $this->applicationData['reference_number'] . '**')
                    ->line('Please keep this reference number safe as you will need it to check your application status.')
                    ->action('Check Application Status', url(route('application.status')));
                break;
                
            case 'verified':
                $mail->line('Your membership application documents have been successfully verified.')
                    ->line('Your application is now being reviewed by the membership committee.')
                    ->line('You will receive another notification when your application has been approved or rejected.')
                    ->action('Check Application Status', url(route('application.status')));
                break;
                
            case 'approved':
                $mail->line('Congratulations! Your membership application has been approved.')
                    ->line('You are now a member of ACICS.')
                    ->line('Please use the link below to set up your account password:')
                    ->action('Set Your Password', url(route('set_password', [
                        'token' => $this->applicationData['token'] ?? '',
                        'email' => $notifiable->email
                    ])))
                    ->line('After setting your password, you will be able to log in to your account and access all member services.');
                break;
                
            case 'rejected':
                $mail->line('We regret to inform you that your membership application has been rejected.')
                    ->line('Reason: ' . ($this->applicationData['rejection_reason'] ?? 'No specific reason provided.'))
                    ->line('If you would like more information or wish to reapply, please contact our office.')
                    ->action('Contact Support', url(route('contact')));
                break;
            
            default:
                $mail->line('There has been an update to your membership application.')
                    ->action('Check Application Status', url(route('application.status')));
        }
        
        return $mail->line('If you have any questions, please contact our support team.')
            ->salutation('Regards, ACICS Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'reference_number' => $this->applicationData['reference_number'] ?? null,
            'message' => $this->getNotificationMessage(),
            'action_url' => $this->getActionUrl(),
        ];
    }
    
    /**
     * Get the notification subject based on type.
     *
     * @return string
     */
    protected function getSubject(): string
    {
        switch ($this->type) {
            case 'submitted':
                return 'ACICS Membership Application Received';
            case 'verified':
                return 'ACICS Membership Application Verified';
            case 'approved':
                return 'ACICS Membership Application Approved';
            case 'rejected':
                return 'ACICS Membership Application Status Update';
            default:
                return 'ACICS Membership Application Update';
        }
    }
    
    /**
     * Get the notification greeting based on type.
     *
     * @return string
     */
    protected function getGreeting(): string
    {
        switch ($this->type) {
            case 'submitted':
                return 'Thank you for your application!';
            case 'verified':
                return 'Your documents have been verified!';
            case 'approved':
                return 'Congratulations!';
            case 'rejected':
                return 'Application Status Update';
            default:
                return 'Hello!';
        }
    }
    
    /**
     * Get the notification message for database storage.
     *
     * @return string
     */
    protected function getNotificationMessage(): string
    {
        switch ($this->type) {
            case 'submitted':
                return 'Your membership application has been received. Reference: ' . 
                       ($this->applicationData['reference_number'] ?? 'N/A');
            case 'verified':
                return 'Your membership application documents have been verified and are under review.';
            case 'approved':
                return 'Your membership application has been approved! Set your password to access your account.';
            case 'rejected':
                return 'Your membership application has been rejected. Reason: ' . 
                       ($this->applicationData['rejection_reason'] ?? 'Contact support for details.');
            default:
                return 'There has been an update to your membership application.';
        }
    }
    
    /**
     * Get the action URL based on notification type.
     *
     * @return string
     */
    protected function getActionUrl(): string
    {
        switch ($this->type) {
            case 'approved':
                return route('set_password', [
                    'token' => $this->applicationData['token'] ?? '',
                    'email' => $this->applicationData['email'] ?? ''
                ]);
            case 'rejected':
                return route('contact');
            default:
                return route('application.status');
        }
    }
}