<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignupLead extends Model
{
    protected $table = 'signup_leads';

    protected $fillable = [
        'role',
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'code',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
