<?php

namespace App\Listeners;

use App\Events\RegisterNewUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\signUp;
use App\Jobs\registerProcess;
use Carbon\Carbon;

class SendMail
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
        //sending email notification for verification of emails its a listner of event ==> RegisterNewUserevent class

        //$event->user->notify(new signUp($event->user));//Without Queues sending mails


        //As sending mails require some time I dispatch it into registerProcess Job Queue
        registerProcess::dispatch($event->user);
        //dispatch(new registerProcess($event->user)); //another way of dispatching
        
    }
}
