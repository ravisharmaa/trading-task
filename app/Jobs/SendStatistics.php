<?php

namespace App\Jobs;

use App\Http\ValueObjects\MailMessageValueObject;
use App\Mail\SendStatisticsEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(private readonly MailMessageValueObject $messageValueObject)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->messageValueObject->getTo())->send(new SendStatisticsEmail($this->messageValueObject));
    }
}
