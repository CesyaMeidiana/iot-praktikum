<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AssignmentSubmitted extends Notification
{
    public function __construct(public $assignment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'assignment_submitted',
            'title'   => 'Tugas Terkirim',
            'message' => 'Tugas "' . $this->assignment->title . '" berhasil disubmit',
            'url'     => route('mahasiswa.tugas.show', $this->assignment->id),
        ];
    }
}