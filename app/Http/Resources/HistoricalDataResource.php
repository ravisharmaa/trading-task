<?php

namespace App\Http\Resources;

use App\Http\Requests\HistoricalQuoteRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoricalDataResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return collect($this->resource)->filter(
            function ($looped) use ($request) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->timestamp;
                $endDate = Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->timestamp;
                $parsed = Carbon::createFromTimestamp($looped['date'])->timestamp;
                return $parsed >= $startDate && $parsed <= $endDate;
            }
        );
    }
}
