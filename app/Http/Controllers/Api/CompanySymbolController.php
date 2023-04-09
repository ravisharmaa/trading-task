<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanySymbolResource;
use App\Models\CompanySymbol;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanySymbolController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $companySymbol = CompanySymbol::query()
            ->when(request()->filled('symbol'), function ($query) {
                $wildCard = '%'.request('symbol').'%';
                $query->where('symbol', 'like', $wildCard);
            })
            ->select('id', 'symbol')
            ->get();

        return CompanySymbolResource::collection($companySymbol);
    }
}
