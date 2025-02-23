<?php

namespace App\Http\Requests\Quotation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ages' => 'required|string|regex:/^\d+(?:,\d+)*$/', // Validates comma-separated numbers
            'currency_id' => ['required', Rule::in(['EUR', 'GBP', 'USD'])],
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'ages.regex' => 'The ages must be comma-separated numbers (e.g., "25,30,35")',
            'currency_id.in' => 'The currency code must be one of: EUR, GBP, USD',
        ];
    }

    /**
     * Get the validated ages as an array
     */
    public function getAges(): array
    {
        return array_map('intval', explode(',', $this->validated()['ages']));
    }
}
