<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    protected $table = 'traffic_logs';

    protected $fillable = [
        'ip',
        'country',
        'city',
        'day',
        'month',
        'year',
        'code',
    ];
}
