<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'license_number'
    ];

    /**
     * Get the user that owns the Office
     *
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function empolyees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function landmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Landmark::class);
    }

    public function presents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Present::class);
    }

    public function guides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Guide::class);
    }
}
