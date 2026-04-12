<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCategoryRequest extends FormRequest
{
    public function mappedAttributes() : array {
        $attributeMap = [
            'data.attributes.nameEn' => 'name_en',
            'data.attributes.nameKu' => 'name_ku',
            'data.attributes.nameAr' => 'name_ar',
            'data.attributes.icon' => 'icon',
            'data.relationships.parent.data.id' => 'parent_id',
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
