<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class CategoryReorderRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('editcategories');
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:categories,id',
            'items.*.position' => 'required|integer|min:1',
        ];
    }
}
