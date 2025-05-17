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

    /**
     * Get the pilgrim associated with the User
     *
     * @return HasOne
     */
    public function hajjType(): HasOne
    {
        return $this->hasOne(HajjType::class);
    }
}
