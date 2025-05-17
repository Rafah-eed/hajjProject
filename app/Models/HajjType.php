<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HajjType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'trip_id',
        'pilgrim_id'
    ];




    /**
     * Get the user that owns the employee
     *
     * @return BelongsTo
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the user that owns the employee
     *
     * @return BelongsTo
     */
    public function pilgrim(): BelongsTo
    {
        return $this->belongsTo(Pilgrim::class);
    }


}
