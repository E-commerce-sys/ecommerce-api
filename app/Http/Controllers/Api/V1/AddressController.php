<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AddressResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AddressController extends Controller
{
    public function userAddresses() {
        return AddressResource::collection(
            QueryBuilder::for(auth('sanctum')->user()->addresses())
            ->orderBy('created_at', 'desc')
            ->get()
        );
    }
}
