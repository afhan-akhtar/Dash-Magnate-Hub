<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'professional_id',
        'plan_id',
        'name',
        'number',
        'expiry_date',
        'cvc',
        'code',
    ];

    protected $hidden = ['cvc'];

    protected $casts = [
        'professional_id' => 'integer',
        'plan_id'         => 'integer',
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function plan()
    {
        return $this->belongsTo(PlanPurchase::class, 'plan_id');
    }
}
