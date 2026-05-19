<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    protected $table = 'plan_purchases';

    protected $fillable = [
        'professional_id',
        'plan_id',
        'type',
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
        'billing_first_name',
        'billing_last_name',
        'billing_business_name',
        'billing_abn',
        'billing_email',
        'billing_phone',
        'billing_address',
        'status',
        'code',
    ];

    protected $casts = [
        'professional_id' => 'integer',
        'plan_id' => 'integer',
        'type' => 'integer',
        'listing_limit' => 'integer',
        'duration_days' => 'integer',
        'premium_marking_limit' => 'integer',
        'premium_marking_used' => 'integer',
        'price' => 'decimal:2',
        'inclusions' => 'array',
        'status' => 'integer',
        'expiry' => 'date',
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function package()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'plan_id');
    }
}
