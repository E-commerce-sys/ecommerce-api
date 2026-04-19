<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseProductRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseProductRequest 
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
                'sometimes', 
                'string', 
                'max:32', 
                'min:3',
                Rule::unique('products', 'name_en')->ignore(intval($this->route('product_id')))
            ],
            'data.attributes.nameAr' => [
                'sometimes', 
                'string', 
                'max:32', 
                'min:3', 
                Rule::unique('products', 'name_ar')->ignore(intval($this->route('product_id')))
            ],
            'data.attributes.nameKu' => [
                'sometimes', 
                'string', 
                'max:32', 
                'min:3', 
                Rule::unique('products', 'name_ku')->ignore(intval($this->route('product_id')))
            ],
            'data.attributes.descriptionEn' => [
                'sometimes', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.descriptionAr' => [
                'sometimes', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.descriptionKu' => [
                'sometimes', 
                'string', 
                'max:255', 
                'min:3', 
            ],
            'data.attributes.price' => [
                'sometimes', 
                'numeric', 
                'min:0',
            ],
            'data.attributes.isBestSelling' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.isFeatured' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.isNewArrival' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.isNew' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.newArrivalImage' => [
                'sometimes',
                'image', 
                'mimes:jpeg,png,jpg', 
                'max:2048',
            ],
            'data.attributes.hasDiscount' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.discountPercentage' => [
                'sometimes', 
                'numeric', 
                'min:0', 
                'max:100',
            ],
            'data.attributes.hasSize' => [
                'sometimes', 
                'boolean',
            ],
            'data.attributes.hasColor' => [
                'sometimes', 
                'boolean',
            ],
        ];
    }
}
