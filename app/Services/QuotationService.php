<?php

namespace App\Services;

use App\Models\AgeLoad;
use App\Models\Quotation;
use App\Repositories\CurrencyRepository;
use App\Repositories\QuotationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationService
{
    private const FIXED_RATE_PER_DAY = 3;

    public function __construct(
        private QuotationRepository $quotationRepository,
        private CurrencyRepository $currencyRepository
    ) {}

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->quotationRepository->getPaginated($perPage);
    }

    public function create(array $data, array $ages): Quotation
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $totalLoad = $this->calculateTotalLoad($ages, $totalDays);

        $currency = $this->currencyRepository->findByCode($data['currency_id']);
        if (!$currency) {
            throw new ModelNotFoundException('Currency not found');
        }

        return $this->quotationRepository->create([
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
