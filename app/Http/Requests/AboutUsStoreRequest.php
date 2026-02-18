<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class AboutUsStoreRequest extends FailedValidation
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        // change permission if needed
        return Auth::user() && Auth::user()->can('editaboutus');

        // OR:
        // return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'address'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',

            'lat'            => 'nullable|numeric|between:-90,90',
            'lng'            => 'nullable|numeric|between:-180,180',

            'working_hours'  => 'nullable|string|max:255',

            'facebook'       => 'nullable|url|max:255',
            'instagram'      => 'nullable|url|max:255',
            'linkedin'       => 'nullable|url|max:255',
            'telegram'       => 'nullable|url|max:255',

            'viber'          => 'nullable|string|max:100',
            'whatsapp'       => 'nullable|string|max:100',
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [
            'lat.numeric' => __('variable.wrong_number'),
            'lng.numeric' => __('variable.wrong_number'),

            'facebook.url'  => __('variable.wrong_url'),
            'instagram.url' => __('variable.wrong_url'),
            'linkedin.url'  => __('variable.wrong_url'),
            'telegram.url'  => __('variable.wrong_url'),

            'address.max' => __('variable.max_255_error'),
            'phone.max'   => __('variable.max_50_error'),
        ];
    }
}
