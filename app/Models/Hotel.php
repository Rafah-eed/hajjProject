<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'hotel_name',
        'rate',
        'address',
    ];
    
    protected $attributes = [
        'rate' => 'numeric',
    ];
    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!is_numeric($model->rate)) {
                throw new \InvalidArgumentException('Rate must be a numeric value.');
            }
        });
    }
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }

    
    public function hotel_trips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HotelTrip::class);
    }
}