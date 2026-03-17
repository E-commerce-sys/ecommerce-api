<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseContactRequest extends FormRequest
{
   public function mappedAttributes() {
        $attributeMap = [
                'data.attributes.name' => 'name',
                'data.attributes.email' => 'email',
                'data.attributes.message' => 'message',
                'data.attributes.phone' => 'phone',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $value = $this->input($key);

                $attributesToUpdate[$attribute] = $value;
            }
        }
        return $attributesToUpdate;
    } 
}
