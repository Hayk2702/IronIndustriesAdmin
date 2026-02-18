<?php
namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class AboutCompanyStoreRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('editaboutcompany');
        // or just return true if you don’t use permissions here
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('variable.this_field_is_required'),
            'title.max' => __('variable.max_150_error'),
            'image.image' => __('variable.wrong_image'),
            'image.mimes' => __('variable.wrong_image'),
            'image.max' => __('variable.image_max_4mb'),
        ];
    }
}
