<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProductStoreRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && (
                Auth::user()->can('createproducts') || Auth::user()->can('editproducts')
            );
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:products,id',

            'service_id' => 'nullable|integer|exists:services,id',

            'title' => 'required|string|max:150',
            'description' => 'nullable|string',

            'price' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',

            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'deleted_image_ids' => 'nullable|array',
            'deleted_image_ids.*' => 'integer|exists:product_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('variable.this_field_is_required'),
            'title.max' => __('variable.max_150_error'),

            'price.max' => __('variable.max_50_error'),
            'size.max' => __('variable.max_50_error'),
            'weight.max' => __('variable.max_50_error'),
            'type.max' => __('variable.max_100_error'),
            'material.max' => __('variable.max_100_error'),

            'images.*.image' => __('variable.wrong_image'),
            'images.*.mimes' => __('variable.wrong_image'),
            'images.*.max' => __('variable.image_max_4mb'),

            'service_id.exists' => __('variable.not_found_error'),
        ];
    }
}
