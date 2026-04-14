<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseProductRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.attributes.nameEn' => 'name_en',
            'data.attributes.nameAr' => 'name_ar',
            'data.attributes.nameKu' => 'name_ku',
            'data.attributes.descriptionEn' => 'description_en',
            'data.attributes.descriptionAr' => 'description_ar',
            'data.attributes.descriptionKu' => 'description_ku',
            'data.attributes.price' => 'price',
            'data.attributes.isBestSelling' => 'is_best_selling',
            'data.attributes.isFeatured' => 'is_featured',
            'data.attributes.isNewArrival' => 'is_new_arrival',
            'data.attributes.isNew' => 'is_new',
            'data.attributes.newArrivalImage' => 'new_arrival_image',
            'data.attributes.hasDiscount' => 'has_discount',
            'data.attributes.discountPercentage' => 'discount_percentage',
            'data.attributes.hasSize' => 'has_size',
            'data.attributes.hasColor' => 'has_color',

            // relationship
            'data.relationships.category.data.id' => 'category_id',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    public function mappedImages(): array
    {
        $images = [];

        if ($this->has('data.included.images')) {
            foreach ($this->input('data.included.images') as $index => $image) {
                $images[] = [
                    'image' => data_get($image, 'attributes.image'),
                    'is_primary' => data_get($image, 'attributes.isPrimary', false),
                ];
            }
        }

        return $images;
    }

    public function mappedVariants(): array
    {
        $variants = [];

        if ($this->has('data.included.variants')) {
            foreach ($this->input('data.included.variants') as $variant) {
                $variants[] = [
                    'stock' => data_get($variant, 'attributes.stock'),

                    // nested color
                    'color' => [
                        'name' => data_get($variant, 'included.color.attributes.name'),
                        'hex_code' => data_get($variant, 'included.color.attributes.hexCode'),
                    ],

                    // nested size
                    'size' => [
                        'size_label' => data_get($variant, 'included.size.attributes.sizeLabel'),
                        'extra_price' => data_get($variant, 'included.size.attributes.extraPrice'),
                    ],
                ];
            }
        }

        return $variants;
    }
}