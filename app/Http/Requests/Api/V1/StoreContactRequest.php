<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseContactRequest;

class StoreContactRequest extends BaseContactRequest 
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => 'required|email',
            'data.attributes.phone' => 'required|string',
            'data.attributes.message' => 'required|string|max:500',
            'data.relationships.user.data.id' => 'required|exists:users,id',
        ];
    }
}
