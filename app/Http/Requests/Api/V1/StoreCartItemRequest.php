<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCartItemRequest;
use Illuminate\Validation\Rule;

class StoreCartItemRequest extends BaseCartItemRequest 
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
        $productId = $this->input('data.relationships.product.data.id');
        return [
            'data.attributes.quantity' => ['required', 'integer', 'min:1'],
            'data.relationships.product.data.id' => ['required', 'integer', 'exists:products,id'],
            'data.relationships.productColor.data.id' => [
                'sometimes',
                'integer',
                Rule::exists('product_colors', 'id')
                    ->where(fn ($query) =>
                        $query->where('product_id', $productId)
                    ),
            ],
            'data.relationships.productSize.data.id' => [
                'sometimes',
                'integer',
                Rule::exists('product_sizes', 'id')
                    ->where(fn ($query) =>
                        $query->where('product_id', $productId)
                    ),
            ],
        ];
    }
}
