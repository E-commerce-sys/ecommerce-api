<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use Spatie\QueryBuilder\QueryBuilder;

class AddressController extends ApiController
{
    public function userAddresses() {
        return AddressResource::collection(
            QueryBuilder::for(auth('sanctum')->user()->addresses())
            ->orderBy('created_at', 'desc')
            ->get()
        );
    }

    public function store(StoreAddressRequest $request) {
        $address = auth('sanctum')->user()->addresses()->create($request->mappedAttributes());
        return $this->success(new AddressResource($address), 'Address created successfully!', 201);
    }
}
