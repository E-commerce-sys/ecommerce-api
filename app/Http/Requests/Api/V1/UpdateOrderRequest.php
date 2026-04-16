<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseOrderRequest;
use App\Models\Order;
use App\Rules\CancelOrder;

class UpdateOrderRequest extends BaseOrderRequest
{
    public ?Order $order = null;
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        $rules = [
            'data.attributes.status' => ['sometimes', 'string', 'in:pending,preparing,shipping,delivering,arrived,cancelled'],
        ];

        return $rules;
    }
}
