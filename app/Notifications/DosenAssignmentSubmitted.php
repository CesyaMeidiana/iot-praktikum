<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DosenAssignmentSubmitted extends Notification
{
    public function __construct(public $assignment, public $student) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'dosen_assignment_submitted',
            'title'   => 'Tugas Dikumpulkan',
            'message' => "{$this->student->name} mengumpulkan tugas \"{$this->assignment->title}\"",
            'url'     => route('dosen.tugas.show', $this->assignment->id),
        ];
    }
}