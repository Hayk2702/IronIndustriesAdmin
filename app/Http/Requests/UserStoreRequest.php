<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FailedValidation
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!Auth::user() || !(Auth::user()->can('createusers') || Auth::user()->can('editusers'))){
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
        $rule = [
            'name'       => 'required|string|max:150',
            'email'         => 'required|email|max:150|unique:users,email',
            'role_id'       => 'array',
            'permission_id' => 'array',

        ];

        if ($this->has('id') AND $this->id) {
            $rule['id'] = 'exists:users,id';
            $rule['email'] .= ',' . $this->id.',id';
            if (($this->has('password') AND $this->password != '') OR ($this->has('password_confirmation') AND $this->password_confirmation != '')) {
                $rule['password'] = 'required|string|min:8|confirmed';
            }
        } else{
            $rule['password'] = 'required|string|min:8|confirmed';
            $rule['email'] .= ',NULL,id';
        }
        $rule['email'] .= ',deleted_at,NULL';

        if($this->has('role_id') AND $this->role_id){
            $rule['role_id.*'] = 'exists:roles,id';
        }
        if($this->has('permission_id') AND $this->permission_id){
            $rule['permission_id.*'] = 'exists:permissions,id';
        }
        return $rule;

    }

    public function messages()
    {
        return [
            'id.exists'                 => __('variable.not_found_error'),
            'name.required'          => __('variable.this_field_is_required'),
            'name.string'            => __('variable.this_field_is_required_and_has_been_text'),
            'name.max'               => __('variable.max_150_error'),
            'email.required'            => __('variable.this_field_is_required'),
            'email.email'               => __('variable.wrong_email'),
            'email.unique'              => __('variable.already_exist_error'),
            'email.max'                 => __('variable.max_150_error'),
            'password.required'         => __('variable.this_field_is_required'),
            'password.confirmed'        => __('variable.wrong_passwords'),
            'password.min'              => __('variable.min_password_error'),
            'role_id.*.exists'          => __('variable.not_found_error'),
            'permission_id.*.exists'    => __('variable.not_found_error'),
        ];
    }
}
