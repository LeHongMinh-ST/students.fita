<?php

namespace App\Jobs;

use App\Services\CrawlDataLearningOutcomeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlDataLearningOutcomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $studentCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($studentCode)
    {
        $this->studentCode = $studentCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->studentCode) {
                app(CrawlDataLearningOutcomeService::class)->crawlData($this->studentCode);
            }
        }catch (\Exception $exception) {
            $this->failed($exception);
        }

    }

    public function failed($exception = null)
    {
        $exception?->getMessage();
    }
}
