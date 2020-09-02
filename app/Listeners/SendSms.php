<?php

namespace App\Listeners;

use App\Events\RegisterNewUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\sms;
class SendSms
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
        //sending sms to new register user its a listner of event ==> RegisterNewUserevent class
       // $event->user->notify(new sms($event->user));
    }
}
