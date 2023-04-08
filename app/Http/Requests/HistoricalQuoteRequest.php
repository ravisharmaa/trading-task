<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class HistoricalQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'company_symbol' => 'required',
            'start_date' => 'required|date|lte:end_date|lte:'. $this->getRequiredDateFormat(),
            'end_date' => 'required|date|gte:start_date|lte:'. $this->getRequiredDateFormat()
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'start_date.lte' => 'The start date must be less than or equal to the end date and current date.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.gte' => 'The end date must be greater than or equal to the start date.',
            'end_date.lte' => 'The end date must be less than or equal to the current date.',
        ];
    }
    private function getRequiredDateFormat(): string
    {
        return now()->format('Y-m-d');
    }
}
