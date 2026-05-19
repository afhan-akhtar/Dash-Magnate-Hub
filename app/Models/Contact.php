<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_deleted',
        'code',
    ];

    protected $casts = [
        'is_deleted' => 'integer',
    ];
}
