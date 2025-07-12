<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportSeat extends Model
{
    use HasFactory;

    protected $fillable = ['transport_id', 'seat', 'price'];

     protected $primaryKey = 'id';
     
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user that owns the Payment
     *
     * @return BelongsTo
     */
    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }

        protected $softDelete = true;

        
    public function delete()
    {
        parent::delete();
        $this->update(['deleted_at' => Carbon::now()]);
    }

    public static function find($id)
    {
        return static::withTrashed()->find($id);
    }
}