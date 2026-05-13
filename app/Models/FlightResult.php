<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightResult extends Model
{
    protected $fillable = [
        'flight_search_id',
        'airline',
        'airline_logo',
        'price',
        'departure_time',
        'arrival_time',
        'duration',
        'stops',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function search()
    {
        return $this->belongsTo(FlightSearch::class, 'flight_search_id');
    }
}
