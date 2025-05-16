<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pilgrim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'passport_photo',
        'code'
    ];

    /**
     * Get the user that owns the Pilgrim
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the visas for the Pilgrim
     *
     * @return HasMany
     */
    public function visas(): HasMany
    {
        return $this->hasMany(Visa::class);
    }
}
