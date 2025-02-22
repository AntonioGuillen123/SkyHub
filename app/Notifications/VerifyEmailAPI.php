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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    private function generateSignedURL(object $notifiable)
    {
        $userId = $notifiable->id;
        $userEmail = $notifiable->email;

        return URL::temporarySignedRoute(
            'apiVerifyEmail',
            now()->addMinutes(60),
            [
                'id' => $userId,
                'email' => sha1($userEmail)
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
