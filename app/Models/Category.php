<?php

namespace App\Models;

use App\Traits\HasDocuments;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasDocuments;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'url',
        'ref_id',
        'account_id',
        'status',
        'active',
        'code',
    ];

    protected $casts = [
        'status'     => 'integer',
        'active'     => 'integer',
        'account_id' => 'integer',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
