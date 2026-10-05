<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use  Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class ReloadFreeRadiusJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = Process::run ('sudo -n /usr/bin/systemctl restart freeradius');
            
            if($result->successful()){
                Log::info('FreeRADIUS restart successfully');
            } else{
                Log::error('Failed to restart FreeRADIUS'.$result->errorOutput());
            }
    }
}
