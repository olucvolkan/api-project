<?php

namespace App\Services;

use App\Models\AgeLoad;
use App\Models\Currency;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationService
{
    private const FIXED_RATE_PER_DAY = 3;

 
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Quotation::with('currency')->latest()->paginate($perPage);
    }

    public function create(array $data, array $ages): Quotation
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $totalLoad = $this->calculateTotalLoad($ages, $totalDays);

        $currency = Currency::where('code', $data['currency_id'])->firstOrFail();

        return Quotation::create([
            'total' => $totalLoad,
            'currency_id' => $currency->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);
    }

    /**
     * Calculate total load for given ages and days
     * Formula: Sum of (Fixed Rate * Age Load * Trip Length) for each age
     */
    private function calculateTotalLoad(array $ages, int $totalDays): float
    {
        $totalLoad = 0;

        foreach ($ages as $age) {
            $ageLoad = AgeLoad::getLoadByAge($age);
            if ($ageLoad) {
                $personCost = self::FIXED_RATE_PER_DAY * $ageLoad->load * $totalDays;
                $totalLoad += $personCost;
            }
        }

        return abs(round($totalLoad, 2));
    }
}
