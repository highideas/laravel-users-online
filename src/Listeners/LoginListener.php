<?php

namespace HighIdeas\UsersOnline\Listeners;

class LoginListener
{
    /**
     * Handle the event.
     *
     * @param  auth.login  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->user !== null) {
            $event->user->setCache(config('session.lifetime'));
        }
    }
}