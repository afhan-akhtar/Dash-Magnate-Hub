<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'blog_id',
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'code',
    ];

    protected $casts = [
        'status'  => 'integer',
        'blog_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
