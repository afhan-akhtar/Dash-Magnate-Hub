<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'collection',
        'path',
        'url',
        'original_name',
        'mime_type',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
