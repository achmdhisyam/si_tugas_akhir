<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JadwalSidang;

class SidangDidaftarkanNotification extends Notification
{
    use Queueable;

    protected $jadwalSidang;

    /**
     * Create a new notification instance.
     */
    public function __construct(JadwalSidang $jadwalSidang)
    {
        $this->jadwalSidang = $jadwalSidang;
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
            'title' => 'Pendaftaran Sidang Baru',
            'message' => $this->jadwalSidang->skripsi->mahasiswa->name . ' telah mendaftar Sidang ' . $this->jadwalSidang->jenis . '. Segera jadwalkan.',
            'url' => '/admin/sidang', // Kita asumsikan URL admin sidang ada di /admin/sidang
        ];
    }
}
