<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Spatie\Activitylog\Models\Activity;


class LoginListener
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
    public function handle(object $event): void{
        activity('users')
        ->performedOn($event->user)
        ->causedBy($event->user)
        ->event('login') 
        ->withProperties([
           // 'ip' => request()->ip(),
           // 'user_agent' => request()->userAgent()
        ])
        ->log('User logged in');
    }
}
