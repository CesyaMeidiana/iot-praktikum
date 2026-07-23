<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Group;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'nim_nip',
    'angkatan',
    'phone',
    'kelas',
    'photo',
    'status',
    'last_login',
    'otp_code',
    'otp_expires_at',
    'email_verified_at',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function groups()
        {
            return $this->belongsToMany(
                Group::class,
                'group_members',
                'student_id',
                'group_id'
            );
        }

        public function kelompokDibuat()
        {
            return $this->hasMany(Group::class, 'dosen_id');
        }

        public function classrooms()
{
    return $this->belongsToMany(
        Classroom::class,
        'classroom_student',
        'student_id',
        'classroom_id'
    );
}

        public function joinedClassrooms()
        {
            return $this->belongsToMany(
                Classroom::class,
                'classroom_student',
                'student_id',
                'classroom_id'
            );
        }

    public function praktikumSessions()
{
    return $this->hasMany(PraktikumSession::class);
}

}