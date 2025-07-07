<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pilgrim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'health_state',
        'passport_photo',
        'personal_identity',
        'personal_photo',
    
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