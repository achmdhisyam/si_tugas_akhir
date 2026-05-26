<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JadwalSidang;

class JadwalSidangDitetapkanNotification extends Notification
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
        $role = $notifiable->role;
        $url = '/dashboard'; 
        
        if ($role === 'mahasiswa') {
            $url = '/mahasiswa/sidang';
        } elseif ($role === 'dosen') {
            $url = '/dosen/jadwal-menguji';
        }

        return [
            'title' => 'Jadwal Sidang Ditetapkan',
            'message' => 'Jadwal Sidang ' . $this->jadwalSidang->jenis . ' untuk ' . $this->jadwalSidang->skripsi->mahasiswa->name . ' telah ditetapkan pada ' . \Carbon\Carbon::parse($this->jadwalSidang->tanggal)->translatedFormat('d M Y H:i') . ' di ' . $this->jadwalSidang->ruangan . '.',
            'url' => $url,
        ];
    }
}
