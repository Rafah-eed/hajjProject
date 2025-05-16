<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip_Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'guide_id',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}
