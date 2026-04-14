<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCategoryRequest;
use Illuminate\Validation\Rule;

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
            'data.attributes.nameEn' => [
                'required', 
                'string', 
                'max:32', 
                'min:3', 
                'regex:/^[A-Za-z0-9]+$/', 
                'unique:categories,name_en'
            ],
            'data.attributes.nameKu' => [
                'required', 
                'string', 
                'max:32', 
                'min:3', 
                'regex:/^[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\d\s]+$/u', 'unique:categories,name_ku'
            ],
            'data.attributes.nameAr' => [
                'required', 
                'string', 
                'max:32', 
                'min:3', 
                'regex:/^[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\d\s]+$/u', 'unique:categories,name_ar'
            ],
            'data.attributes.icon' => [
                'required_with:data.relationships.parent.data.id',
                'image', 
                'mimes:jpeg,png,jpg', 
                'max:2048'
            ],
            'data.relationships.parent.data.id' => [
                'required_with:data.attributes.icon',
                'integer',
                Rule::exists('categories', 'id')->whereNull('parent_id')
            ],
        ];
    }
}
