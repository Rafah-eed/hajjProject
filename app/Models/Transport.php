<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'company_name',
        'description',
        'transport_type',// جوي و بري
    ];


    public function transport_seats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransportSeat::class);
    }

    /**
     * Get the user that owns the Payment
     *
     * @return BelongsTo
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
