<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'office_id',
        'trip_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function trips(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Trip::class);
    }

    public function rateGuides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RateGuide::class);
    }

    public function rateTrips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RateTrip::class);
    }
}