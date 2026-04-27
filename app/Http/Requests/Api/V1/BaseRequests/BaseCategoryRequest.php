<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCategoryRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
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

    public function messages(): array
    {
        return [
            'data.attributes.nameEn.required' => 'The English category name is required.',
            'data.attributes.nameEn.string' => 'The English category name must be text.',
            'data.attributes.nameEn.max' => 'The English category name may not be longer than 32 characters.',
            'data.attributes.nameEn.min' => 'The English category name must be at least 3 characters.',
            'data.attributes.nameEn.regex' => 'The English category name may only contain English letters, numbers, and spaces.',
            'data.attributes.nameEn.unique' => 'A category with this English name already exists.',
            'data.attributes.nameKu.required' => 'The Kurdish category name is required.',
            'data.attributes.nameKu.string' => 'The Kurdish category name must be text.',
            'data.attributes.nameKu.max' => 'The Kurdish category name may not be longer than 32 characters.',
            'data.attributes.nameKu.min' => 'The Kurdish category name must be at least 3 characters.',
            'data.attributes.nameKu.regex' => 'The Kurdish category name may only contain Kurdish letters, numbers, and spaces.',
            'data.attributes.nameKu.unique' => 'A category with this Kurdish name already exists.',
            'data.attributes.nameAr.required' => 'The Arabic category name is required.',
            'data.attributes.nameAr.string' => 'The Arabic category name must be text.',
            'data.attributes.nameAr.max' => 'The Arabic category name may not be longer than 32 characters.',
            'data.attributes.nameAr.min' => 'The Arabic category name must be at least 3 characters.',
            'data.attributes.nameAr.regex' => 'The Arabic category name may only contain Arabic letters, numbers, and spaces.',
            'data.attributes.nameAr.unique' => 'A category with this Arabic name already exists.',
            'data.attributes.icon.required_with' => 'The icon is required when a parent category is selected.',
            'data.attributes.icon.image' => 'The category icon must be an image file.',
            'data.attributes.icon.mimes' => 'The category icon must be a JPEG, PNG, or JPG file.',
            'data.attributes.icon.max' => 'The category icon may not be larger than 2 MB.',
            'data.relationships.parent.data.id.required_with' => 'A parent category must be selected when uploading an icon.',
            'data.relationships.parent.data.id.integer' => 'The parent category id must be a whole number.',
            'data.relationships.parent.data.id.exists' => 'The selected parent category must be a top-level category.',
        ];
    }
}
