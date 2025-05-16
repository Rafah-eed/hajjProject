<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'type',
        'regiment_name',
        'days_num_makkah',
        'days_num_madinah',
        'price',
        'start_date',
        'is_active'
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

    public function trip_guides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Guide::class);
    }
}
