<?php

namespace App\Listeners;

use App\Events\AddressEvent;
use App\Models\UserAddress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveAddressListener
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
    public function handle(AddressEvent $event): void
    {
        $locations = ['Dhaka','Khulna','Barishal','Chittagram','Rajsahi','Rongpur','Cumilla'];
        
        UserAddress::create([
            'user_id' => $event->user?->id,
            'location' => $locations[array_rand($locations)]
        ]);
    }
}
