<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Resources\Api\V1\ContactResource;
use App\Models\Contact;
use Spatie\QueryBuilder\QueryBuilder;

class ContactController extends ApiController
{
    public function index() {
        $this->authorize('viewAny', Contact::class);
        return ContactResource::collection(
            QueryBuilder::for(Contact::class)
            ->allowedIncludes(Contact::allowedIncludes())
            ->paginate(10)
        );
    }

    public function store(StoreContactRequest $request) {
        $contact = Contact::create($request->mappedAttributes());
        return new ContactResource($contact);
    }
}
