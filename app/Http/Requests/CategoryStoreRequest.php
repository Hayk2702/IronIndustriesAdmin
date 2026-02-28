<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class CategoryStoreRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && (Auth::user()->can('createcategories') || Auth::user()->can('editcategories'));
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150',
            'description' => 'nullable|string',

            // multiple images:
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',

            // for edit
            'id' => 'nullable|exists:services,id',
            'deleted_image_ids' => 'nullable|array',
            'deleted_image_ids.*' => 'integer',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => __('variable.this_field_is_required'),
            'title.max' => __('variable.max_150_error'),
            'slug.required' => __('variable.this_field_is_required'),
            'slug.max' => __('variable.max_150_error'),

            'images.array' => __('variable.wrong_image'),
            'images.*.image' => __('variable.wrong_image'),
            'images.*.mimes' => __('variable.wrong_image'),
            'images.*.max' => __('variable.image_max_4mb'),
        ];
    }
}
