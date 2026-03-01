<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class PriceShowRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('showprices');
    }

    public function rules(): array
    {
        return [
            'perPage' => 'nullable|integer|min:1|max:200',
            'sortBy' => 'nullable|string|max:50',
            'sortDesc' => 'nullable|string',
            'filter' => 'nullable|array',
        ];
    }
}
