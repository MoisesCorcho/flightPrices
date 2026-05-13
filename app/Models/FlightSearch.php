<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSearch extends Model
{
    protected $fillable = [
        'user_id',
        'origin',
        'destination',
        'date',
        'target_price',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'target_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasMany(FlightResult::class);
    }
}
