<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pilgrim_id',
        'trip_id',
        'visa_file',
        'status',
        'request_number'

    ];

    /**
     * Get the pilgrim that owns the Visa
     *
     * @return BelongsTo
     */
    public function pilgrim(): BelongsTo
    {
        return $this->belongsTo(Pilgrim::class);
    }

    /**
     * Get the trip that owns the Visa
     *
     * @return BelongsTo
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
