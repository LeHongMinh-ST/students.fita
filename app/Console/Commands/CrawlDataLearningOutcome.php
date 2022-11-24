<?php

namespace App\Console\Commands;

use App\Jobs\CrawlDataLearningOutcomeJob;
use Illuminate\Console\Command;

class CrawlDataLearningOutcome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'space:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CrawlDataLearningOutcomeJob::dispatch('637942');
        return 0;
    }
}
