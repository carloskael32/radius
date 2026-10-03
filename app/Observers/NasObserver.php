<?php

namespace App\Observers;

use App\Jobs\ReloadFreeRadiusJob;
use App\Models\Nas\Nas;

class NasObserver
{
    /**
     * Handle the Nas "created" event.
     */
    public function created(Nas $nas): void
    {
        ReloadFreeRadiusJob::dispatch();
    }

    /**
     * Handle the Nas "updated" event.
     */
    public function updated(Nas $nas): void
    {
        ReloadFreeRadiusJob::dispatch();
    }

    /**
     * Handle the Nas "deleted" event.
     */
    public function deleted(Nas $nas): void
    {
        ReloadFreeRadiusJob::dispatch();
    }

    /**
     * Handle the Nas "restored" event.
     */
    public function restored(Nas $nas): void
    {
        //
    }

    /**
     * Handle the Nas "force deleted" event.
     */
    public function forceDeleted(Nas $nas): void
    {
        //
    }
}
