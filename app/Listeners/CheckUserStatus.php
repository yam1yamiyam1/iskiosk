<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckUserStatus
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Check if user status is inactive
        if ($user->status == '0') {
            Auth::logout();  // Log the user out
            return Redirect::route('login')->withErrors(['status' => 'Your account is inactive. Please contact support.']);
        }
    }
}
