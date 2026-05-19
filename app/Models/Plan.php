<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    protected $fillable = [
        'type',
        'professional_id',
        'name',
        'slug',
        'expiry',
        'listing_limit',
        'price',
        'duration_days',
        'timeframe_label',
        'premium_marking_limit',
        'premium_marking_used',
        'inclusions',
        'is_catalog',
        'status',
        'code',
    ];

    protected $casts = [
        'type'            => 'integer',
        'professional_id' => 'integer',
        'listing_limit'   => 'integer',
        'duration_days'   => 'integer',
        'premium_marking_limit' => 'integer',
        'premium_marking_used'  => 'integer',
        'price'           => 'decimal:2',
        'inclusions'      => 'array',
        'is_catalog'      => 'boolean',
        'status'          => 'integer',
        'expiry'          => 'date',
    ];

    public function scopeCatalog($query)
    {
        return $query->where('is_catalog', true);
    }

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function purchases()
    {
        return $this->hasMany(PlanPurchase::class, 'plan_id');
    }
}
