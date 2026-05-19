<?php

namespace App\Models;

use App\Traits\HasDocuments;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasDocuments;

    protected $table = 'chats';

    protected $fillable = [
        'user_id',
        'professional_id',
        'project_id',
        'message',
        'send',
        'status',
        'code',
    ];

    protected $casts = [
        'user_id'         => 'integer',
        'professional_id' => 'integer',
        'project_id'      => 'integer',
        'send'            => 'integer',
        'status'          => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
