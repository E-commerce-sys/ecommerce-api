<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class ContactSubmissionController extends ApiController
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Contact::class);

        return ContactResource::collection(
            QueryBuilder::for(Contact::class)
                ->allowedIncludes(Contact::allowedIncludes())
                ->paginate(10)
        );
    }
}
