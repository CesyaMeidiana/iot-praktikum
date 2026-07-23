<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PraktikumSessionStarted extends Notification
{
    public function __construct(public $session) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'praktikum_running',
            'title'   => 'Praktikum Sedang Berjalan',
            'message' => 'Praktikum kamu masih berjalan dan belum diselesaikan',
            'url'     => route('mahasiswa.praktikum.show', $this->session->id),
        ];
    }
}