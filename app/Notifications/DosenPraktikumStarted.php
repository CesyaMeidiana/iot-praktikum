<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DosenPraktikumStarted extends Notification
{
    public function __construct(public $session) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $mahasiswa = $this->session->user->name ?? '-';

        return [
            'type'    => 'dosen_praktikum_started',
            'title'   => 'Praktikum Dimulai',
            'message' => "{$mahasiswa} memulai praktikum #{$this->session->id}",
            'url'     => route('dosen.riwayat.show', $this->session->id),
        ];
    }
}