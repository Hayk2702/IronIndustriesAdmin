<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class PriceStoreRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && (Auth::user()->can('createprices') || Auth::user()->can('editprices'));
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:material_prices,id',

            'material_name' => 'required|string|max:150',

            'cut_cost' => 'nullable|numeric|min:0|max:9999999999',
            'material_cost_per_kg' => 'nullable|numeric|min:0|max:9999999999',
            'density_kg_m3' => 'nullable|numeric|min:0|max:9999999999',
            'bend_price' => 'nullable|numeric|min:0|max:9999999999',

            'thicknesses' => 'nullable|array',
            'thicknesses.*' => 'numeric|min:0.001|max:100000',
        ];
    }

    public function messages(): array
    {
        return [
            'material_name.required' => __('variable.this_field_is_required'),
            'material_name.max' => __('variable.max_150_error'),
        ];
    }
}
