<?php

namespace App\Http\Requests\UserGoal;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class AddUserGoalRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            // 'user_id' => 'required|exists:users,id',
            'goal_type' => [
                'required',
                Rule::in([0, 1, 2]) // 0: lose, 1: gain, 2: maintain
            ],

            'start_weight' => 'required|numeric|min:0',
            'target_weight' => 'required|numeric|min:0',


            'start_date' => 'required|date',
            'target_date' => 'required|date|after_or_equal:start_date',

            'status' => 'nullable|integer|in:0,1,2,3',

        ];
    }

    public function messages()
    {
        return [
            // 'user_id.required' => __('validation.required', ['attribute' => __('Mã người dùng')]),
            'goal_type.required' => __('validation.required', ['attribute' => __('Loại mục tiêu')]),
            'start_weight.required' => __('validation.required', ['attribute' => __('Cân nặng ban đầu')]),
            'target_weight.required' => __('validation.required', ['attribute' => __('Mục tiêu cân nặng')]),
            'start_date.required' => __('validation.required', ['attribute' => __('Ngày bắt đầu')]),
            'target_date.required' => __('validation.required', ['attribute' => __('Ngày mục tiêu')]),
        ];
    }
}
