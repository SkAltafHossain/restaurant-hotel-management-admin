<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('kitchen.orders', function () {
    return true;
});

Broadcast::channel('waiter.orders', function () {
    return true;
});

Broadcast::channel('delivery.orders', function () {
    return true;
});

Broadcast::channel('orders', function () {
    return true;
});
