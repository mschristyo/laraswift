<?php

namespace App\Listeners;

use Auth;

class UserEventsSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
	{

    Auth::user()->update([
        'last_login_at' => \Carbon\Carbon::now()->toDateTimeString(),
        'last_login_ip' => request()->getClientIp()
    ]);

		// Logging activity for user login
		activity()
		->performedOn(Auth::user())
		->withProperties([
      'name'=>Auth::user()->username? Auth::user()->username:Auth::user()->email,
      'by'=>Auth::user()->username? Auth::user()->username:Auth::user()->email
    ])
		->causedBy(Auth::user())
		->log('Account signed-in');
	}

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
	{
		// Logging activity for user login
		activity()
		->performedOn(Auth::user())
		->withProperties([
      'name'=>Auth::user()->username? Auth::user()->username:Auth::user()->email,
      'by'=>Auth::user()->username? Auth::user()->username:Auth::user()->email
    ])
		->causedBy(Auth::user())
		->log('Account signed-out');
	}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventsSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventsSubscriber@onUserLogout'
        );
    }

}
