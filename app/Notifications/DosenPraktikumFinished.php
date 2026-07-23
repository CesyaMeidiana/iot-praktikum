<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DosenPraktikumFinished extends Notification
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
            'type'    => 'dosen_praktikum_finished',
            'title'   => 'Praktikum Diselesaikan',
            'message' => "{$mahasiswa} menyelesaikan praktikum #{$this->session->id}",
            'url'     => route('dosen.riwayat.show', $this->session->id),
        ];
    }
}