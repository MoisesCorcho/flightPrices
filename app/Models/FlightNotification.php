<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightNotification extends Model
{
    protected $fillable = [
        'user_id',
        'flight_result_id',
        'origin',
        'destination',
        'date',
        'airline',
        'price',
        'departure_time',
        'arrival_time',
        'duration',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function result()
    {
        return $this->belongsTo(FlightResult::class, 'flight_result_id');
    }
}
