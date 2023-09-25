<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AllNotifications extends Notification
{
    use Queueable;
    protected $user_level;
    protected $msg;
    /**
     * Create a new notification instance.
     */
    public function __construct($user_level, $msg)
    {
        $this->user_level = $user_level;
        $this->message = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->msg,
            'user_level' => $this->user_level
        ];
    }

    public function toBroadcast($notifiable){
        return new BrodcastMessage([
            'message' => $this->msg,
            'user_level' => $this->user_level
        ]);
    }

    public function broadcastOn(){
        return ['my-channel']
    }
}
