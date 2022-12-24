<?php

namespace App\Listeners;

use Dcblogdev\MsGraph\Models\MsGraphToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewMicrosoft365SignInListener
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
    public function handle($event)
    {
        $tokenId = $event->token['token_id'];
        $token = MsGraphToken::find($tokenId)->first();
        dd($token);
    }
}
