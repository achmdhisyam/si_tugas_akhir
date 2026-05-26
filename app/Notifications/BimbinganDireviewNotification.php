<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Bimbingan;

class BimbinganDireviewNotification extends Notification
{
    use Queueable;

    protected $bimbingan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bimbingan $bimbingan)
    {
        $this->bimbingan = $bimbingan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
            'type' => 'bimbingan_direview',
            'bimbingan_id' => $this->bimbingan->id,
            'title' => 'Review Logbook Bimbingan',
            'message' => 'Dosen pembimbing telah memberikan reviu dan feedback pada bimbingan Anda.',
            'url' => route('bimbingan.index'),
        ];
    }
}
