<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ServiceShowRequest extends FailedValidation
{
    public function authorize(): bool
    {
        return Auth::user() && Auth::user()->can('showservices');
    }

    public function rules(): array
    {
        return [];
    }
}
