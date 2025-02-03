<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgeLoad extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'from_range',
        'max_range',
        'load'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from_range' => 'integer',
        'max_range' => 'integer',
        'load' => 'float',
    ];

    /**
     * Get age loads within a specific range
     *
     * @param int $age
     * @return self|null
     */
    public static function getLoadByAge(int $age): ?self
    {
        return self::where('from_range', '<=', $age)
            ->where('max_range', '>=', $age)
            ->first();
    }
}
