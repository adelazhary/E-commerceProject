<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class imageUplaod implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 120;
    public $tries = 3;
    public $backoff = [2, 5, 10];
    /* The `public  = 3;` line in the code snippet is setting the maximum number of
    exceptions that the job will be allowed to fail before it is considered as permanently failed.
    In this case, the job will be allowed to fail up to 3 times (` = 3;`) before it is
    marked as permanently failed. */
    public $maxExceptions = 3;
    // public $deleteWhenMissingModels = true;
    public $maxTime = 60;
    public $maxJobs = 3;
   /* The line `public  = 3;` in the code snippet is setting the maximum number of attempts
   that the job will make to execute before it is considered as permanently failed. In this case,
   the job will attempt to execute up to 3 times (`= 3;`) before it is marked as permanently failed.
   This parameter helps control the number of times the job will be retried in case of failures. */
    public $maxAttempts = 3;
    public $task = 'image upload';
    /**
     * Create a new job instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        throw new \Exception("Error Processing Request", 1);
        log::info("image upload job" . " " . now() . " " . $this->task);
        // Log::info("message from image upload job" . " " . now());
    }
    public function failed(\Exception $exception)
    {
        Log::error("image upload job failed" . " " . $exception->getMessage());
    }
}
