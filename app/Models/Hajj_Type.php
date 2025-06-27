<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hajj_Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];
    
    public function visas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Visa::class);
    }
    
}