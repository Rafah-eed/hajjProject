<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Landmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'office_id',
        'description',
        'address'
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
