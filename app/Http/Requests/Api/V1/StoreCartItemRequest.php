<?php
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCartItemRequest;
use App\Models\ProductVariant;

class StoreCartItemRequest extends BaseCartItemRequest
{
    public ?ProductVariant $productVariant = null;

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

        // caching product variant on the request
        $productVariantId = $this->input('data.relationships.productVariant.data.id');
        if ($productVariantId) {
            $this->productVariant = ProductVariant::find((int) $productVariantId);
        }
    }

    public function rules(): array
    {
        return [
            'data.attributes.quantity' => ['required', 'integer', 'min:1', 'max:500'],

            'data.relationships.productVariant.data.id' => [
                'required',
                'integer',
                'exists:product_variants,id',
            ],
        ];
    }
}