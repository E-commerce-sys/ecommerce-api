<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Requests\Api\V1\UpdateAddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use Spatie\QueryBuilder\QueryBuilder;

class AddressController extends ApiController
{
    public function userAddresses() {
        return AddressResource::collection(
            QueryBuilder::for(auth('sanctum')->user()->addresses())
            ->orderBy(config('app.main_order_by_field'), 'desc')
            ->get()
        );
    }

    public function store(StoreAddressRequest $request) {
        $address = auth('sanctum')->user()->addresses()->create($request->mappedAttributes());
        return $this->success(new AddressResource($address), 'Address created successfully!', 201);
    }

    public function update(UpdateAddressRequest $request, $address_id) {
        $address = auth('sanctum')->user()->addresses()->findOrFail($address_id);
        $address->update($request->mappedAttributes());
        return $this->success(new AddressResource($address), 'Address updated successfully!');
    }

    public function destroy($address_id) {
        $address = auth('sanctum')->user()->addresses()->findOrFail($address_id);
        $address->delete();
        return $this->ok([], 'Address deleted successfully!');
    }
}
