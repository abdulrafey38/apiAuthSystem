<?php

namespace App\Listeners;

use App\Events\RegisterNewUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\slack;
class SendSlack
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
     * @param  RegisterNewUserEvent  $event
     * @return void
     */
    public function handle(RegisterNewUserEvent $event)
    {   
        //sending slack notification its a listner of event ==> RegisterNewUserevent class
         $event->user->notify(new slack($event->user));
    }
}
