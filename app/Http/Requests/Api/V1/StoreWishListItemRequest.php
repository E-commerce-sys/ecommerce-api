<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseWishListItemRequest;
use Illuminate\Validation\Rule;

class StoreWishListItemRequest extends BaseWishListItemRequest
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
            'data.relationships.product.data.id' => ['required', 'exists:products,id', Rule::unique('wish_list_items', 'product_id')->where('user_id', $this->user()->id)],
        ];
    }
}
