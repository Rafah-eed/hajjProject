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
        'license_number'
    ];

    /**
     * Get the user that owns the Office
     *
     *
     */


    public function empolyees(): HasMany
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
}