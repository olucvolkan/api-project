<?php

namespace App\Repositories;

use App\Models\Quotation;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationRepository
{
    public function create(array $data): Quotation
    {
        return Quotation::create($data);
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Quotation::with('currency')->latest()->paginate($perPage);
    }
} 