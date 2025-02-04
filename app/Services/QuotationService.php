<?php

namespace App\Services;

use App\Models\AgeLoad;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationService
{
    private const FIXED_RATE_PER_DAY = 3;

    /**
     * Get paginated quotations
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Quotation::with('currency')->latest()->paginate($perPage);
    }

    public function create(array $data, array $ages): Quotation
    {
        // Calculate total days between dates
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $endDate->diffInDays($startDate) + 1;

        // Calculate total load for all ages
        $totalLoad = $this->calculateTotalLoad($ages, $totalDays);

        // Create quotation with calculated total
        return Quotation::create([
            'total' => $totalLoad,
            'currency_id' => $data['currency_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);
    }

    /**
     * Calculate total load for given ages and days
     * Formula: Total = Fixed Rate * Age Load * Trip Length
     */
    private function calculateTotalLoad(array $ages, int $totalDays): float
    {
        $totalLoad = 0;

        foreach ($ages as $age) {
            $ageLoad = AgeLoad::getLoadByAge($age);

            if ($ageLoad) {
                // Apply formula: Fixed Rate * Age Load * Trip Length
                $totalLoad += self::FIXED_RATE_PER_DAY * $ageLoad->load * $totalDays;
            }
        }

        return round($totalLoad, 2);
    }
}
