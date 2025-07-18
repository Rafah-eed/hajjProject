<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'user_id',
        'position_name',
        'salary'
    ];

    /**
     * Get the user that owns the employee
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function trip_employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trip_Employee::class);
    }
}