<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminAssignmentCreated extends Notification
{
    public function __construct(public $assignment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $dosen     = $this->assignment->lecturer->name ?? '-';
        $classroom = $this->assignment->classroom->name ?? '-';

        return [
            'type'    => 'admin_assignment_created',
            'title'   => 'Tugas Baru Dibuat',
            'message' => "Dosen {$dosen} membuat tugas \"{$this->assignment->title}\" untuk kelas {$classroom}",
            'url'     => route('admin.dashboard'),
        ];
    }
}