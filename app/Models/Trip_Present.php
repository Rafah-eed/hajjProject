<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip_Present extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'present_id',

    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function present(): BelongsTo
    {
        return $this->belongsTo(Present::class);
    }
}
