<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Skripsi;

class PengingatBimbinganNotification extends Notification
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengingat Bimbingan Mahasiswa',
            'message' => 'Ketua Program Studi mengingatkan Anda untuk meninjau progres skripsi mahasiswa: ' . $this->skripsi->mahasiswa->name . ' yang sudah lama tidak melakukan bimbingan.',
            'url' => route('dosen.bimbingan.show', $this->skripsi->id),
            'type' => 'warning',
        ];
    }
}
