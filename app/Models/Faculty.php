<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Faculty extends Authenticatable
{
    use Notifiable;

    protected $table = 'faculty';

    protected $fillable = [
        'full_name',
        'email',
        'faculty_id',
        'password',
        'id_file_path',
        'status',
        'profile_image', // store 'avatars/xxxx.jpg' here
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['name','avatar_url','initials','display_name','display_email'];

    /* Map $model->name to your full_name column */
    public function getNameAttribute(): string
    {
        return (string)($this->attributes['full_name'] ?? '');
    }
    public function setNameAttribute($value): void
    {
        $this->attributes['full_name'] = (string)$value;
    }

    /* Exact same idea as User::getAvatarUrlAttribute(), with normalization */
    public function getAvatarUrlAttribute(): ?string
    {
        $val = (string)($this->profile_image ?? '');
        if ($val === '') return null;

        // If absolute (CDN/remote), just return it
        if (preg_match('#^https?://#i', $val)) return $val;

        // Normalize to 'avatars/filename.ext'
        $path = ltrim($val, '/');
        if (!str_contains($path, '/')) {
            $path = 'avatars/'.$path;
        }

        // Use the public disk just like User model
        return Storage::url($path);  // -> '/storage/avatars/...'
    }

    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        if ($name === '') return 'F';
        $parts = preg_split('/\s+/', $name);
        $a = mb_substr($parts[0] ?? '', 0, 1);
        $b = mb_substr($parts[1] ?? '', 0, 1);
        $init = ($a.$b) ?: mb_substr($name, 0, 2);
        return mb_strtoupper($init);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Faculty User';
    }

    public function getDisplayEmailAttribute(): string
    {
        return (string)($this->email ?? 'faculty@sys.test.ph');
    }
}
