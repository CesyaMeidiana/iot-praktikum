<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AssignmentCreated extends Notification
{
    public function __construct(public $assignment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'assignment_created',
            'title'   => 'Tugas Baru',
            'message' => 'Tugas "' . $this->assignment->title . '" telah ditambahkan',
            'url'     => route('mahasiswa.tugas.show', $this->assignment->id),
        ];
    }
}