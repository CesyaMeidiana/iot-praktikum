<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminPraktikumFinished extends Notification
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
            'type'    => 'admin_praktikum_finished',
            'title'   => 'Praktikum Diselesaikan',
            'message' => "{$mahasiswa} menyelesaikan praktikum #{$this->session->id}",
            'url'     => route('admin.riwayat.show', $this->session->id),
        ];
    }
}