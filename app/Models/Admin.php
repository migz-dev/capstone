<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

protected $fillable = [
    'full_name', 'email', 'password_hash', 'is_active', 'profile_image',
];

    protected $hidden = [
        'password_hash',
    ];

    // Tell Laravel which column stores the password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
