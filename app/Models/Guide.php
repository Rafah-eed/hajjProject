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
        'trip_id',
        'office_id',
        'position_name'
    ];

    /**
     * Get the user that owns the Guide
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function trip_guides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Guide::class);
    }

}
