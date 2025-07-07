<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guide_id',
        'rate',
        'comment',
    ];

    public function guides(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Guide::class);
    }
}