<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminAssignmentSubmitted extends Notification
{
    public function __construct(public $assignment, public $student) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'admin_assignment_submitted',
            'title'   => 'Tugas Dikumpulkan',
            'message' => "{$this->student->name} mengumpulkan tugas \"{$this->assignment->title}\"",
            'url'     => route('admin.dashboard'),
        ];
    }
}