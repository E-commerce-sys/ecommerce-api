<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Resources\Api\V1\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request) {
        $contact = Contact::create($request->mappedAttributes());
        return new ContactResource($contact);
    }
}
