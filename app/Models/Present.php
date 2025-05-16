<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Present extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'details'
    ];

    public function trip_presents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Present::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
