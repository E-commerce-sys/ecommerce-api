<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCategoryRequest;

class StoreCategoryRequest extends BaseCategoryRequest
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
            'data.attributes.nameEn' => ['required', 'string', 'max:32', 'min:3'],
            'data.attributes.nameKu' => ['required', 'string', 'max:32', 'min:3'],
            'data.attributes.nameAr' => ['required', 'string', 'max:32', 'min:3'],
            'data.attributes.icon' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'data.relationships.parent.data.id' => ['sometimes', 'integer', 'exists:categories,id'],
        ];
    }
}
