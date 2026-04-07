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

    protected function prepareForValidation(): void
    {
        // caching order on the request
        $orderId = $this->route('order_id');
        if ($orderId) {
            $this->order = auth('sanctum')->user()->orders()->findOrFail($orderId);
        }
    }

    
    public function rules(): array
    {
        $rules = [
            'data.attributes.status' => ['sometimes', 'string', new CancelOrder($this->order->status), 'in:pending,preparing,shipping,delivering,arrived,cancelled'],
        ];

        return $rules;
    }
}
