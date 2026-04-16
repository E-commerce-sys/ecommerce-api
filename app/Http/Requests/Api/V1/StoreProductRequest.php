<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseProductRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends BaseProductRequest
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
                'unique:products,name_en'
            ],
            'data.attributes.nameAr' => [
                'required', 
                'string', 
                'max:32', 
                'min:3', 
                'unique:products,name_ar'
            ],
            'data.attributes.nameKu' => [
                'required', 
                'string', 
                'max:32', 
                'min:3', 
                'unique:products,name_ku'
            ],
            'data.attributes.descriptionEn' => [
                'required', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.descriptionAr' => [
                'required', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.descriptionKu' => [
                'required', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.price' => [
                'required', 
                'numeric', 
                'min:0',
            ],
            'data.attributes.isBestSelling' => [
                'required', 
                'boolean',
            ],
            'data.attributes.isFeatured' => [
                'required', 
                'boolean',
            ],
            'data.attributes.isNewArrival' => [
                'required', 
                'boolean',
            ],
            'data.attributes.isNew' => [
                'required', 
                'boolean',
            ],
            'data.attributes.newArrivalImage' => [
                'sometimes',
                'image', 
                'mimes:jpeg,png,jpg', 
                'max:2048',
            ],
            'data.attributes.hasDiscount' => [
                'required', 
                'boolean',
            ],
            'data.attributes.discountPercentage' => [
                'required', 
                'numeric', 
                'min:0', 
                'max:100',
            ],
            'data.attributes.hasSize' => [
                'required', 
                'boolean',
            ],
            'data.attributes.hasColor' => [
                'required', 
                'boolean',
            ],

            // relationships
            'data.relationships.category.data.id' => [
                'required', 
                Rule::exists('categories', 'id')->whereNotNull('parent_id'),
            ],

            // included
            'data.included.images' => [
                'required', 
                'array', 
                'min:1',
            ],
            'data.included.images.*.attributes.image' => [
                'required', 
                'image', 
                'mimes:jpeg,png,jpg', 
                'max:2048',
            ],
            'data.included.images.*.attributes.isPrimary' => [
                'required', 
                'boolean',
            ],
            'data.included.variants' => [
                'required', 
                'array', 
                'min:1',
            ],
            'data.included.variants.*.attributes.stock' => [
                'required', 
                'numeric', 
                'min:0',
            ],
            'data.included.variants.*.included.color.attributes.hexCode' => [
                'sometimes', 
                'string', 
                'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            ],
            'data.included.variants.*.included.size.attributes.sizeLabel' => [
                'sometimes', 
                'string', 
                'max:32', 
                'min:1', 
            ],
            'data.included.variants.*.included.size.attributes.extraPrice' => [
                'sometimes', 
                'numeric', 
                'min:0',
            ]
        ];
    }
}
