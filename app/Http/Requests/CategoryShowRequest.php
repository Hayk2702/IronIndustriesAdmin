<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class CategoryShowRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('showcategories');
    }

    public function rules(): array
    {
        return [];
    }
}
