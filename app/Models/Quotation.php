<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total',
        'currency_id',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total' => 'decimal:2'
    ];

    /**
     * Get the currency that owns the quotation.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
} 