<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProductShowRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('showproducts');
    }

    public function rules(): array
    {
        return [];
    }
}
