<?php

namespace App\Http\ValueObjects;

use Carbon\Carbon;

readonly class MailMessageValueObject
{
    public function __construct(
        private string $company,
        private string $startDate,
        private string $endDate,
        private string $to
    )
    {
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
