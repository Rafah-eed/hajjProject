<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_active',
        'numOfReservations',
        'trip_code'
    ];

    public function visas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Visa::class);
    }

    public function trip_presents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Present::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the pilgrim associated with the User
     *
     * @return HasOne
     */
    public function hajjType(): HasOne
    {
        return $this->hasOne(HajjType::class);
    }

    public function trip_employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Employee::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }


}
