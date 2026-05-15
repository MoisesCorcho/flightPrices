<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_search_id',
        'airline',
        'flight_number',
        'airline_logo',
        'origin_name',
        'destination_name',
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
