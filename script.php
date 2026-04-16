<?php
use App\Models\Order;
use App\States\Preparing;
use Illuminate\Support\Facades\Storage;

$order =  Order::find(1);
$order->status->transitionTo(
    $order->status::next()
);