<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
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
            'data.attributes.quantity' => ['required', 'integer', 'min:1'],
            'data.relationships.product.data.id' => ['required', 'integer', 'exists:products,id'],
            'data.relationships.productColor.data.id' => ['sometimes', 'integer', 'exists:product_colors,id'],
            'data.relationships.productSize.data.id' => ['sometimes', 'integer', 'exists:product_sizes,id'],
        ];
    }
}
