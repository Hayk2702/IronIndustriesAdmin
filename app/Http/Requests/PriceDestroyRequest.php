<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class PriceDestroyRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('deleteprices');
    }

    public function rules(): array
    {
        return [];
    }
}
