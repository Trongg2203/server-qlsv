<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|max:255',
            'password' => 'required|min:6|max:50',
            // 'remember' => 'required|boolean',
            // 'type' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.required', ['attribute' => __('auth.email')]),
            'email.max' => __('validation.max', ['attribute' => __('auth.username'), 'max' => 255]),

            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
            'password.max' => __('validation.max', ['attribute' => __('auth.password'), 'max' => 50]),
            'password.min' => __('validation.min', ['attribute' => __('auth.password'), 'min' => 6]),

            // 'remember.required' => __('validation.required', ['attribute' => __('auth.remember')]),
            // 'remember.boolean' => __('validation.boolean', ['attribute' => __('auth.remember')]),

            // 'type.integer' => __('validation.integer', ['attribute' => __('auth.type')]),
        ];
    }
}
