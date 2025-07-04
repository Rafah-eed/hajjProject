<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportSeat extends Model
{
    use HasFactory;

    protected $fillable = ['transport_id', 'seat', 'price'];

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user that owns the Payment
     *
     * @return BelongsTo
     */
    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }
}