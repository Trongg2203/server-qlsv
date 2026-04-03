<?php

namespace App\Http\Requests\ModuleAction;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class AddModuleActionRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('module_actions', 'code'),
            ],
            'name' => 'required|max:255|string',
            'description' => 'nullable|string|max:500',
            'module_id' => 'nullable|string',
            'status' => 'nullable|integer',

        ];
    }

    public function messages()
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('moduleAction.code')]),
            'code.max' => __('validation.max.string', ['attribute' => __('moduleAction.code'), 'max' => 50]),

            'name.required' => __('validation.required', ['attribute' => __('moduleAction.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('moduleAction.name'), 'max' => 255]),

            'description.string' => __('validation.string', ['attribute' => __('moduleAction.description')]),
            'description.max' => __('validation.max.string', ['attribute' => __('moduleAction.description'), 'max' => 500]),

            'module_id.string' => __('validation.string', ['attribute' => __('moduleAction.module_id')]),
            'status.integer' => __('validation.integer', ['attribute' => __('moduleAction.status')]),
        ];
    }
}
