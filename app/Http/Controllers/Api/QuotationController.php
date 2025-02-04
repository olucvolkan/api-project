<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Resources\QuotationResource;
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

        return response()->json(
            QuotationResource::collection($quotations)
        );
    }

    /**
     * Store a newly created quotation.
     */
    public function store(StoreQuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->create(
            $request->validated(),
            $request->getAges()
        );

        $quotation->load('currency');

        return (new QuotationResource($quotation))
            ->response()
            ->setStatusCode(201);
    }
}
