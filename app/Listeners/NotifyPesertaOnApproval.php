<?php

namespace App\Listeners;

use App\Events\PendaftaranApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyPesertaOnApproval
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PendaftaranApproved $event): void
    {
        //
    }
}
