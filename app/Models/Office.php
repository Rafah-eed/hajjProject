<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'license_number',
        'office_email',
        'office_password'
    ];

    /**
     * Get the user that owns the Office
     *
     *
     */


    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function transports(): HasMany
    {
        return $this->hasMany(Transport::class);
    }

    public function landmarks(): HasMany
    {
        return $this->hasMany(Landmark::class);
    }

    public function presents(): HasMany
    {
        return $this->hasMany(Present::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get the guide associated with the User
     *
     * @return HasMany
     */
    public function guides(): HasMany
    {
        return $this->HasMany(Guide::class);
    }

    public function transport_trips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransportTrip::class);
    }

    public function hotel_trips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HotelTrip::class);
    }
}