<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class PreorderShowRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('showpreorders');
    }

    public function rules(): array
    {
        return [
            'perPage' => 'nullable|integer|min:1|max:100',
            'sortBy' => 'nullable|string',
            'sortDesc' => 'nullable',
            'filter' => 'nullable|array',
        ];
    }
}
