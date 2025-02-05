<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Services\QuotationService;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(
        private QuotationService $quotationService
    ) {}

    public function showForm(): View
    {
        return view('quotations.form');
    }

    public function store(StoreQuotationRequest $request)
    {
        $quotation = $this->quotationService->create(
            $request->validated(),
            $request->getAges()
        );

        return redirect()->route('quotations.form')
            ->with('quotation', $quotation)
            ->with('success', 'Quotation created successfully!');
    }
}
