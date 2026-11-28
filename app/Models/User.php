<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    public const ROLE_PENGGUNA = 'pengguna';

    public const ROLE_KONSELOR = 'konselor';

    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_PENGGUNA,
        self::ROLE_KONSELOR,
        self::ROLE_ADMIN,
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, MustVerifyEmail, Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'prodi',
        'role',
        'is_available',
        'is_active',
        'email',
        'password',
        'face_descriptor',
        'face_registered_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'face_descriptor',
    ];

    public function isPengguna(): bool
    {
        return $this->role === self::ROLE_PENGGUNA;
    }

    public function isKonselor(): bool
    {
        return $this->role === self::ROLE_KONSELOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAvailable(): bool
    {
        return (bool) $this->is_available;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Send the application's branded email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function counselingSessions(): HasMany
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function assignedSessions(): HasMany
    {
        return $this->hasMany(CounselingSession::class, 'counselor_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'face_descriptor' => 'encrypted:array',
            'face_registered_at' => 'datetime',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
