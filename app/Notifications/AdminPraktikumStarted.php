<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminPraktikumStarted extends Notification
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
            'type'    => 'admin_praktikum_started',
            'title'   => 'Praktikum Dimulai',
            'message' => "{$mahasiswa} memulai praktikum #{$this->session->id}",
            'url'     => route('admin.riwayat.show', $this->session->id),
        ];
    }
}