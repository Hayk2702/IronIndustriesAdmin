<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProductDestroyRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('deleteproducts');
    }

    public function rules(): array
    {
        return [];
    }
}
