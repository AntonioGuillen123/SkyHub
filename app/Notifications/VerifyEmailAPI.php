<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailAPI extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->generateSignedURL($notifiable);

        return $this->generateEmail($notifiable, $url);
    }

    private function generateSignedURL(object $notifiable)
    {
        $encryptionAlgorithm = env('MAIL_HASH', 'sha256');
        $expirationMinutes = intval(env('MAIL_EXPIRATION_MINUTES', 30));
        $userId = $notifiable->id;
        $userEmail = $notifiable->email;

        return URL::temporarySignedRoute(
            'apiVerifyEmail',
            now()->addMinutes($expirationMinutes),
            [
                'id' => $userId,
                'hash' => hash($encryptionAlgorithm, $userEmail)
            ]
        );
    }

    private function generateEmail(object $notifiable, string $url)
    {
        $name = $notifiable->name;

        return (new MailMessage)
            ->greeting('Hello ' . $name . '!')
            ->subject('Verify Email')
            ->line('Click the link below to verify your email.')
            ->action('Verify Email', $url)
            ->line('Thank you for using our application!');
    }
}
