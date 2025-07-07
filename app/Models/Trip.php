<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'type',
        'regiment_name',
        'days_num_makkah',
        'days_num_madinah',
        'price',
        'start_date',
        'end_date',
        'is_active',
        'numOfReservations',
        'trip_code'
    ];

    protected $appends = ['enrollNum'];

    public function getEnrollNumAttribute($value)
    {
        return $this->attributes['enrollNum'] ?? 0;
    }
    
    public function checkAvailability(int $spots)
    {
        return $this->numOfReservations >= $spots;
    }

    
    public function visas(): HasMany
    {
        return $this->hasMany(Visa::class);
    }

    public function trip_presents(): HasMany
    {
        return $this->hasMany(Trip_Present::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function trip_employees(): HasMany
    {
        return $this->hasMany(Trip_Employee::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function guides(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Guide::class);
    }

    public function transport_trips(): HasMany
    {
        return $this->hasMany(TransportTrip::class);
    }

    public function hotel_trips(): HasMany
    {
        return $this->hasMany(HotelTrip::class);
    }
}