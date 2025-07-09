<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'hotel_id',
        'office_id',
        'place'
    ];

    public function trip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function hotel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function office(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

}