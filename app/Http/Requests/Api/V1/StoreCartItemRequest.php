<?php
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCartItemRequest;
use App\Models\Product;
use Illuminate\Validation\Rule;

class StoreCartItemRequest extends BaseCartItemRequest
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
        $productId = $this->input('data.relationships.product.data.id');
        if ($productId) {
            $this->product = Product::find((int) $productId);
        }
    }

    public function rules(): array
    {
        $productId = $this->input('data.relationships.product.data.id');

        return [
            'data.attributes.quantity' => ['required', 'integer', 'min:1'],

            'data.relationships.product.data.id' => [
                'required',
                'integer',
                'exists:products,id',
                Rule::unique('cart_items', 'product_id')
                    ->where('cart_id', $this->user()->cart->id), // uses cached relation
            ],

            'data.relationships.productColor.data.id' => [
                Rule::requiredIf($this->product?->has_color ?? false),
                Rule::prohibitedIf(!($this->product?->has_color ?? false)),
                'integer',
                Rule::exists('product_colors', 'id')
                    ->where(fn($q) => $q->where('product_id', $productId)),
            ],

            'data.relationships.productSize.data.id' => [
                Rule::requiredIf($this->product?->has_size ?? false),
                Rule::prohibitedIf(!($this->product?->has_size ?? false)),
                'integer',
                Rule::exists('product_sizes', 'id')
                    ->where(fn($q) => $q->where('product_id', $productId)),
            ],
        ];
    }
}