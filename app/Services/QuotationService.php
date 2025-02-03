<?php

namespace App\Services;

use App\Models\Quotation;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationService
{
    /**
     * Get paginated quotations
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Quotation::with('currency')->latest()->paginate($perPage);
    }

    /**
     * Create a new quotation
     */
    public function create(array $data): Quotation
    {
        return Quotation::create($data);
    }

    /**
     * Update the quotation
     */
    public function update(Quotation $quotation, array $data): Quotation
    {
        $quotation->update($data);
        return $quotation->fresh();
    }

    /**
     * Delete the quotation
     */
    public function delete(Quotation $quotation): bool
    {
        return $quotation->delete();
    }
} 