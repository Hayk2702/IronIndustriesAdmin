<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class RolesGetRequest extends FailedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!(Auth::user() AND (Auth::user()->can('showroles') OR Auth::user()->can('createusers')))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
