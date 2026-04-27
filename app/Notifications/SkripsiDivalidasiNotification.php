<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Skripsi;

class SkripsiDivalidasiNotification extends Notification
{
    use Queueable;

    protected $skripsi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Skripsi $skripsi)
    {
        $this->skripsi = $skripsi;
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
        $statusStr = $this->skripsi->status === 'disetujui' ? 'Disetujui' : 'Ditolak';
        
        return [
            'type' => 'validasi_skripsi',
            'skripsi_id' => $this->skripsi->id,
            'title' => 'Judul Skripsi ' . $statusStr,
            'message' => 'Pengajuan judul Anda telah ' . strtolower($statusStr) . ' oleh Kaprodi.',
            'url' => route('dashboard'),
        ];
    }
}
