<?php

namespace App\Listeners;

use App\Events\BoardNewEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BoardNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BoardNewEvents  $event
     * @return void
     */
    public function handle(BoardNewEvents $event)
    {
        //
    }
}
