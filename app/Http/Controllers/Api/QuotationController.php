<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Requests\Quotation\UpdateQuotationRequest;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function __construct(private QuotationService $quotationService)
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of quotations.
     */
    public function index(): JsonResponse
    {
        $quotations = $this->quotationService->getPaginated();

        return response()->json([
            'data' => $quotations
        ]);
    }

    /**
     * Store a newly created quotation.
     */
    public function store(StoreQuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->create($request->validated());

        return response()->json([
            'message' => 'Quotation created successfully',
            'data' => $quotation->load('currency')
        ], 201);
    }

    /**
     * Display the specified quotation.
     */
    public function show(Quotation $quotation): JsonResponse
    {
        return response()->json([
            'data' => $quotation->load('currency')
        ]);
    }

    /**
     * Update the specified quotation.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation): JsonResponse
    {
        $quotation = $this->quotationService->update($quotation, $request->validated());

        return response()->json([
            'message' => 'Quotation updated successfully',
            'data' => $quotation->load('currency')
        ]);
    }

    /**
     * Remove the specified quotation.
     */
    public function destroy(Quotation $quotation): JsonResponse
    {
        $this->quotationService->delete($quotation);

        return response()->json([
            'message' => 'Quotation deleted successfully'
        ]);
    }
}
