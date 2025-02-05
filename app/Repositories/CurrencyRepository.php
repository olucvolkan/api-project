<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    public function findByCode(string $code): ?Currency
    {
        return Currency::where('code', $code)->first();
    }
} 