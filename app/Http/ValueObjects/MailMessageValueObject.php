<?php

namespace App\Http\ValueObjects;

use Carbon\Carbon;

readonly class MailMessageValueObject
{
    public function __construct(
        private string $company,
        private string $startDate,
        private string $endDate,
        private string $to,
        private array $data
    ) {
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

    public function getData(): array
    {
        return array_filter($this->data, function($filtered) {
            $startDate = Carbon::parse($this->getStartDate())->timestamp;
            $endDate = Carbon::parse($this->getEndDate())->timestamp;
            $parsed = Carbon::createFromTimestamp($filtered['date'])->timestamp;
            return $parsed >= $startDate && $parsed <= $endDate;
        });
    }

    public function format(): array
    {
        return array_map(function($filtered) {
            $filtered['date'] = Carbon::createFromTimestamp($filtered['date'])->format('Y-m-d');
            $filtered['open'] = round($filtered['open'], 2);
            $filtered['high'] = round($filtered['high'], 2);
            $filtered['low'] = round($filtered['low'], 2);
            $filtered['close'] = round($filtered['close'], 2);
            $filtered['volume'] = round($filtered['volume'], 2);
            return $filtered;
        }, $this->getData());
    }
}
