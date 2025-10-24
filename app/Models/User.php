<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'role',
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
            'password' => 'hashed',
        ];
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function idleSessions()
    {
        return $this->hasMany(IdleSession::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    // role constants
    public const ROLE_USER = 1;
    public const ROLE_ADMIN = 2;

    // to get role names
    public const ROLES = [
        self::ROLE_USER => 'User',
        self::ROLE_ADMIN => 'Admin',
    ];

    // to get only admin users
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    // to get only normal users
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }


    public function getRoleNameAttribute(): string
    {
        return self::ROLES[$this->role] ?? 'Unknown';
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(UserObserver::class);
    }
}
