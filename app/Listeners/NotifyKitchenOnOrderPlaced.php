<?php

namespace App\Listeners;

use App\Events\OrderPlaced;

class NotifyKitchenOnOrderPlaced
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
    public function handle(OrderPlaced $event): void
    {
        $event->order->load('user', 'table', 'orderItems.item');
    }
}
