<?php
use App\Models\Order;
use App\Models\User;
use App\States\Preparing;
use Illuminate\Support\Facades\Storage;

$user = User::find(1);
print_r($user->roles);