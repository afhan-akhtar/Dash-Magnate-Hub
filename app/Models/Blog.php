<?php

namespace App\Models;

use App\Traits\HasDocuments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Blog extends Model
{
    use HasDocuments;

    protected $table = 'blogs';

    protected $fillable = [
        'name',
        'description',
        'content',
        'account_id',
        'blog_category_id',
        'writer_name',
        'ref_id',
        'status',
        'active',
        'views',
        'url',
        'code',
    ];

    protected $casts = [
        'status' => 'integer',
        'active' => 'integer',
        'views' => 'integer',
        'account_id' => 'integer',
        'blog_category_id' => 'integer',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }
}
