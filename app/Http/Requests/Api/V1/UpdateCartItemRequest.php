<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCartItemRequest;
use App\Models\Product;
use Illuminate\Validation\Rule;

class UpdateCartItemRequest extends BaseCartItemRequest
{

    public ?Product $product = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // caching users's cart on the user relation
        $this->user()->setRelation(
            'cart',
            $this->user()->cart()->firstOrCreate([])
        );

        // caching product on the request
        $productId = $this->productId;
        if ($productId) {
            $this->product = Product::find((int) $productId);
        }
    }

    public function rules(): array
    {
        $productId = $this->productId;
        return [
            'data.attributes.quantity' => ['sometimes', 'integer', 'min:1'],

            'data.relationships.productColor.data.id' => [
                'sometimes',
                Rule::prohibitedIf(!($this->product?->has_color ?? false)),
                'integer',
                Rule::exists('product_colors', 'id')
                    ->where(fn($q) => $q->where('product_id', $productId)),
            ],

            'data.relationships.productSize.data.id' => [
                'sometimes',
                Rule::prohibitedIf(!($this->product?->has_size ?? false)),
                'integer',
                Rule::exists('product_sizes', 'id')
                    ->where(fn($q) => $q->where('product_id', $productId)),
            ],
        ];
    }
}
