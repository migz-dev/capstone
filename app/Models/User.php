<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'avatar_path', // <-- NEW
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

    /* =========================
     |  View helpers / Accessors
     |=========================*/

    // e.g., "MC" from "Miguel Caluya" or first two letters of email
    public function getInitialsAttribute(): string
    {
        $name = trim((string) $this->name);
        if ($name !== '') {
            $parts = preg_split('/\s+/', $name);
            $a = mb_substr($parts[0] ?? '', 0, 1);
            $b = mb_substr($parts[1] ?? '', 0, 1);
            $init = ($a . $b) ?: mb_substr($name, 0, 2);
        } else {
            $init = mb_substr((string) $this->email, 0, 2);
        }
        return mb_strtoupper($init);
    }

    // Public URL for avatar (or null)
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Student';
    }

    public function getDisplayEmailAttribute(): string
    {
        return (string) $this->email;
    }
}
