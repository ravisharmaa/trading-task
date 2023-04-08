<?php

namespace App\Console\Commands;

use App\Http\Clients\ClientType;
use App\Http\Clients\HttpClientService;
use App\Http\ValueObjects\CompanySymbolValueObject;
use App\Models\CompanySymbol;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StoreCompanySymbolsCommand extends Command
{
    protected $signature = 'app:store-company-symbols';

    protected $description = 'Command description';

    public function handle(
        HttpClientService $clientService,
        CompanySymbolValueObject $valueObject
    ): int
    {
        $this->warn('This command will remove all existing data from the table');
        $data = $clientService->getData(
            ClientType::COMPANY_SYMBOL,
            env('COMPANY_SYMBOLS_PATH_COMPONENT')
        );
        if (empty($data)) {
            $this->warn('Got empty data.. quitting now');
            return false;
        }
        DB::beginTransaction();
        CompanySymbol::query()->delete();
        collect($data)->each(function($chunked) use ($valueObject) {
                $valueObject->fromArray($chunked)->transformToCompanySymbol();
                DB::commit();
        });
        return true;
    }
}
