<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class I1Login
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
     * @param  object  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $user      = $event->getSaml2User();
        $attrs     = $user->getAttributes();

        $u = Admin::updateOrCreate([
            'email'     => $attrs['email'][0],
        ], [
            'name'   => $attrs['name'][0],
            'userid' => $user->getUserId(),
        ]);

        if (! $u->exists) {
            $u->save();
        }

        Auth::guard('admin')->login($u);
    }
}
