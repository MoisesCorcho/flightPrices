<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSearch extends Model
{
    use HasFactory;

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

    public function getFrequencyHoursAttribute(): int
    {
        $daysToFlight = now()->diffInDays($this->date, false);

        if ($daysToFlight < 7) {
            return 6;
        }

        if ($daysToFlight < 30) {
            return 12;
        }

        return 24;
    }

    public function getFrequencyLabelAttribute(): string
    {
        if ($this->date->endOfDay()->isPast()) {
            return __('Flight Expired');
        }

        return match ($this->frequency_hours) {
            6 => __('Intensive (every 6h)'),
            12 => __('Twice daily (every 12h)'),
            default => __('Daily (every 24h)'),
        };
    }

    public function getNextCheckAtAttribute()
    {
        if (! $this->is_active || $this->date->endOfDay()->isPast()) {
            return null;
        }

        $lastUpdate = $this->results()->latest()->first()?->created_at;

        if (! $lastUpdate) {
            return now();
        }

        $dueAt = $lastUpdate->copy()->addHours($this->frequency_hours);

        return $dueAt->isPast() ? now() : $dueAt;
    }

    public function getNextCheckTextAttribute(): string
    {
        if (! $this->is_active) {
            return __('Alert paused');
        }

        if ($this->date->endOfDay()->isPast()) {
            return __('Flight has already passed');
        }

        $nextAt = $this->next_check_at;

        if (! $nextAt || $nextAt->isPast() || abs($nextAt->diffInMinutes(now())) <= 1) {
            return __('Next check: on upcoming run');
        }

        return __('Next check: :time', ['time' => $nextAt->diffForHumans()]);
    }
}
