<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';

    protected $fillable = ['location_id', 'name'];

    protected $casts = [
        'location_id' => 'integer',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
