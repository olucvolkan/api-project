<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => number_format($this->total, 2),
            'currency_id' => $this->currency->code,
            'quotation_id' => $this->id
        ];
    }
}
