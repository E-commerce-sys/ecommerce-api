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
                    'image' => $this->file('data.included.images.'.$index.'.attributes.image'),
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
                    'color' => data_get($variant, 'included.color') ? [
                        'hex_code' => data_get($variant, 'included.color.attributes.hexCode'),
                    ] : null,

                    // nested size
                    'size' => data_get($variant, 'included.size') ? [
                        'size_label' => data_get($variant, 'included.size.attributes.sizeLabel'),
                        'extra_price' => data_get($variant, 'included.size.attributes.extraPrice'),
                    ] : null,
                ];
            }
        }

        return $variants;
    }

    public function messages(): array
    {
        return [
            'data.attributes.nameEn.required' => 'The English product name is required.',
            'data.attributes.nameEn.string' => 'The English product name must be text.',
            'data.attributes.nameEn.max' => 'The English product name may not be longer than 32 characters.',
            'data.attributes.nameEn.min' => 'The English product name must be at least 3 characters.',
            'data.attributes.nameEn.unique' => 'A product with this English name already exists.',
            'data.attributes.nameAr.required' => 'The Arabic product name is required.',
            'data.attributes.nameAr.string' => 'The Arabic product name must be text.',
            'data.attributes.nameAr.max' => 'The Arabic product name may not be longer than 32 characters.',
            'data.attributes.nameAr.min' => 'The Arabic product name must be at least 3 characters.',
            'data.attributes.nameAr.unique' => 'A product with this Arabic name already exists.',
            'data.attributes.nameKu.required' => 'The Kurdish product name is required.',
            'data.attributes.nameKu.string' => 'The Kurdish product name must be text.',
            'data.attributes.nameKu.max' => 'The Kurdish product name may not be longer than 32 characters.',
            'data.attributes.nameKu.min' => 'The Kurdish product name must be at least 3 characters.',
            'data.attributes.nameKu.unique' => 'A product with this Kurdish name already exists.',
            'data.attributes.descriptionEn.required' => 'The English product description is required.',
            'data.attributes.descriptionEn.string' => 'The English product description must be text.',
            'data.attributes.descriptionEn.max' => 'The English product description may not be longer than 255 characters.',
            'data.attributes.descriptionEn.min' => 'The English product description must be at least 3 characters.',
            'data.attributes.descriptionAr.required' => 'The Arabic product description is required.',
            'data.attributes.descriptionAr.string' => 'The Arabic product description must be text.',
            'data.attributes.descriptionAr.max' => 'The Arabic product description may not be longer than 255 characters.',
            'data.attributes.descriptionAr.min' => 'The Arabic product description must be at least 3 characters.',
            'data.attributes.descriptionKu.required' => 'The Kurdish product description is required.',
            'data.attributes.descriptionKu.string' => 'The Kurdish product description must be text.',
            'data.attributes.descriptionKu.max' => 'The Kurdish product description may not be longer than 255 characters.',
            'data.attributes.descriptionKu.min' => 'The Kurdish product description must be at least 3 characters.',
            'data.attributes.price.required' => 'The product price is required.',
            'data.attributes.price.numeric' => 'The product price must be a valid number.',
            'data.attributes.price.min' => 'The product price cannot be negative.',
            'data.attributes.isBestSelling.required' => 'Please specify whether the product is a best-selling item.',
            'data.attributes.isBestSelling.boolean' => 'The best-selling value must be true or false.',
            'data.attributes.isFeatured.required' => 'Please specify whether the product is featured.',
            'data.attributes.isFeatured.boolean' => 'The featured value must be true or false.',
            'data.attributes.isNewArrival.required' => 'Please specify whether the product is a new arrival.',
            'data.attributes.isNewArrival.boolean' => 'The new-arrival value must be true or false.',
            'data.attributes.isNew.required' => 'Please specify whether the product is marked as new.',
            'data.attributes.isNew.boolean' => 'The new-product value must be true or false.',
            'data.attributes.newArrivalImage.image' => 'The new-arrival image must be an image file.',
            'data.attributes.newArrivalImage.mimes' => 'The new-arrival image must be a JPEG, PNG, or JPG file.',
            'data.attributes.newArrivalImage.max' => 'The new-arrival image may not be larger than 2 MB.',
            'data.attributes.hasDiscount.required' => 'Please specify whether the product has a discount.',
            'data.attributes.hasDiscount.boolean' => 'The discount flag must be true or false.',
            'data.attributes.discountPercentage.required' => 'The discount percentage is required.',
            'data.attributes.discountPercentage.numeric' => 'The discount percentage must be a valid number.',
            'data.attributes.discountPercentage.min' => 'The discount percentage cannot be negative.',
            'data.attributes.discountPercentage.max' => 'The discount percentage cannot be greater than 100.',
            'data.attributes.hasSize.required' => 'Please specify whether the product has size variants.',
            'data.attributes.hasSize.boolean' => 'The size-variant flag must be true or false.',
            'data.attributes.hasColor.required' => 'Please specify whether the product has color variants.',
            'data.attributes.hasColor.boolean' => 'The color-variant flag must be true or false.',
            'data.relationships.category.data.id.required' => 'Please choose a category for this product.',
            'data.relationships.category.data.id.exists' => 'Products can only be assigned to a child category.',
            'data.included.images.required' => 'At least one product image is required.',
            'data.included.images.array' => 'The product images must be sent as a list.',
            'data.included.images.min' => 'At least one product image is required.',
            'data.included.images.max' => 'A product may not have more than 4 images.',
            'data.included.images.*.attributes.image.required' => 'Each product image file is required.',
            'data.included.images.*.attributes.image.image' => 'Each product image must be an image file.',
            'data.included.images.*.attributes.image.mimes' => 'Each product image must be a JPEG, PNG, or JPG file.',
            'data.included.images.*.attributes.image.max' => 'Each product image may not be larger than 2 MB.',
            'data.included.images.*.attributes.isPrimary.required' => 'Each product image must specify whether it is the primary image.',
            'data.included.images.*.attributes.isPrimary.boolean' => 'The primary-image value must be true or false.',
            'data.included.variants.required' => 'At least one product variant is required.',
            'data.included.variants.array' => 'The product variants must be sent as a list.',
            'data.included.variants.min' => 'At least one product variant is required.',
            'data.included.variants.*.attributes.stock.required' => 'Each product variant must include its stock quantity.',
            'data.included.variants.*.attributes.stock.numeric' => 'Each product variant stock value must be a valid number.',
            'data.included.variants.*.attributes.stock.min' => 'Product variant stock cannot be negative.',
            'data.included.variants.*.included.color.attributes.hexCode.required' => 'A color hex code is required for every variant when the product has color variants.',
            'data.included.variants.*.included.color.attributes.hexCode.prohibited' => 'Color details are not allowed when the product is marked as having no color variants.',
            'data.included.variants.*.included.color.attributes.hexCode.string' => 'The color hex code must be text.',
            'data.included.variants.*.included.color.attributes.hexCode.regex' => 'The color hex code must be a valid 3- or 6-digit hex color, such as #000 or #000000.',
            'data.included.variants.*.included.size.attributes.sizeLabel.required' => 'A size label is required for every variant when the product has size variants.',
            'data.included.variants.*.included.size.attributes.sizeLabel.prohibited' => 'Size details are not allowed when the product is marked as having no size variants.',
            'data.included.variants.*.included.size.attributes.sizeLabel.string' => 'The size label must be text.',
            'data.included.variants.*.included.size.attributes.sizeLabel.max' => 'The size label may not be longer than 32 characters.',
            'data.included.variants.*.included.size.attributes.sizeLabel.min' => 'The size label must be at least 1 character.',
            'data.included.variants.*.included.size.attributes.extraPrice.numeric' => 'The size extra price must be a valid number.',
            'data.included.variants.*.included.size.attributes.extraPrice.min' => 'The size extra price cannot be negative.',
        ];
    }
}
