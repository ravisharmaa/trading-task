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
            'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date|before_or_equal:today'
        ];
    }
    public function getCarbonInstance(string $date): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $date);
    }
}
