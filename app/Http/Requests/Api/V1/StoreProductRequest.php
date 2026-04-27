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
        $hasColor = $this->boolean('data.attributes.hasColor');
        $hasSize = $this->boolean('data.attributes.hasSize');
        
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
                'max:4'
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
                Rule::requiredIf($hasColor),
                Rule::prohibitedIf(!$hasColor),
                'string', 
                'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            ],
            'data.included.variants.*.included.size.attributes.sizeLabel' => [
                Rule::requiredIf($hasSize),
                Rule::prohibitedIf(!$hasSize), 
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

    // /**
    //  * Add custom validation logic after base rules pass.
    //  * AI GENERATED FUNCTION
    //  */
    // public function withValidator($validator): void
    // {
    //     $validator->after(function ($validator) {
    //         $variants = $this->input('data.included.variants', []);

    //         if (empty($variants)) {
    //             return;
    //         }

    //         $hasColor = fn($variant) => isset($variant['included']['color']['attributes']['hexCode'])
    //             && !is_null($variant['included']['color']['attributes']['hexCode']);

    //         $hasSize = fn($variant) => isset($variant['included']['size']['attributes']['sizeLabel'])
    //             && !is_null($variant['included']['size']['attributes']['sizeLabel']);

    //         $colorStates = array_map($hasColor, $variants);
    //         $sizeStates  = array_map($hasSize,  $variants);

    //         // If not all variants agree on having/not having color → inconsistent
    //         if (count(array_unique($colorStates)) > 1) {
    //             $validator->errors()->add(
    //                 'data.included.variants',
    //                 'Color inconsistency: either all variants must have a color or none of them should.'
    //             );
    //         }

    //         // If not all variants agree on having/not having size → inconsistent
    //         if (count(array_unique($sizeStates)) > 1) {
    //             $validator->errors()->add(
    //                 'data.included.variants',
    //                 'Size inconsistency: either all variants must have a size or none of them should.'
    //             );
    //         }
    //     });
    // }
}
