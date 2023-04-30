<?php

namespace App\Listeners;

use App\Events\DateUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserDate
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
     * @param  DateUpdate  $event
     * @return void
     */
    public function handle(DateUpdate $event)
    {
    }
}
