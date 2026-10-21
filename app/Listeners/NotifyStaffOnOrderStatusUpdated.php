<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;

class NotifyStaffOnOrderStatusUpdated
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
    public function handle(OrderStatusUpdated $event): void
    {
        $event->order->load('user', 'table', 'orderItems.item', 'assignedTo');
    }
}
