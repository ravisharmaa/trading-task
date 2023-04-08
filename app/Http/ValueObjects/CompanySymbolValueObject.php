<?php

namespace App\Http\ValueObjects;

use App\Models\CompanySymbol;

readonly class CompanySymbolValueObject
{
    private string $companyName;
    private ?string $symbol;
    private ?string $financialStatus;

    private ?string $marketCategory;

    private ?float $roundLotSize;

    private ?string $securityName;

    private ?string $testIssue;

    public function setCompanyName(string $name): self
    {
        $this->companyName = $name;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setFinancialStatus(string $financialStatus): self
    {
        $this->financialStatus = $financialStatus;

        return $this;
    }

    public function getFinancialStatus(): ?string
    {
        return $this->financialStatus;
    }

    public function setMarketCategory(string $marketCategory): self
    {
        $this->marketCategory = $marketCategory;

        return $this;
    }

    public function getMarketCategory(): ?string
    {
        return $this->marketCategory;
    }

    public function setRoundLotSize(float $size): self
    {
        $this->roundLotSize = $size;

        return $this;
    }

    public function getRoundLotSize(): ?float
    {
        return $this->roundLotSize;
    }

    public function setSecurityName(string $name): self
    {
        $this->securityName = $name;

        return $this;
    }

    public function getSecurityName(): ?string
    {
        return $this->securityName;
    }

    public function setTestIssue(string $testIssue): self
    {
        $this->testIssue = $testIssue;

        return $this;
    }

    public function getTestIssue(): ?string
    {
        return $this->testIssue;
    }

    public function fromArray(array $responseData): CompanySymbolValueObject
    {
        $data = new self();
        $data
            ->setCompanyName($responseData['Company Name'] ?? '')
            ->setFinancialStatus($responseData['Financial Status'] ?? '')
            ->setMarketCategory($responseData['Market Category'] ?? '')
            ->setSymbol($responseData['Symbol'] ?? '')
            ->setSecurityName($responseData['Security Name'] ?? '')
            ->setTestIssue($responseData['Test Issue'] ?? '')
            ->setRoundLotSize($responseData['Round Lot Size'] ?? '');

        return $data;
    }

    public function transformToCompanySymbol(): void
    {
        CompanySymbol::create([
            'name' => $this->getCompanyName(),
            'financial_status' => $this->getFinancialStatus(),
            'market_category' => $this->getMarketCategory(),
            'symbol' => $this->getSymbol(),
            'test_issue' => $this->getTestIssue(),
            'security_name' => $this->getSecurityName(),
            'round_lot_size' => $this->getRoundLotSize(),
        ]);
    }
}
