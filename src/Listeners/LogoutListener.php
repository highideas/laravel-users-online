<?php

namespace HighIdeas\UsersOnline\Listeners;

class LogoutListener
{
    /**
     * Handle the event.
     *
     * @param auth.logout $event
     *
     * @return void
     */
    public function handle($event)
    {
        if ($event->user !== null) {
            $event->user->pullCache();
        }
    }
}
