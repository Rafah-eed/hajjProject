<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'rate',
        'comment',
    ];

    public function trips(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Trip::class);
    }
}
