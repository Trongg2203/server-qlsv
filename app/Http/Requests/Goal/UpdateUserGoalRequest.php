<?php

namespace App\Http\Requests\Goal;

use App\Http\Requests\BaseRequest;

class UpdateUserGoalRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'goal_type'          => 'sometimes|integer|in:0,1,2',
            'start_weight'       => 'sometimes|numeric|min:30|max:300',
            'target_weight'      => 'sometimes|numeric|min:30|max:300',
            'weekly_change_rate' => 'sometimes|numeric|min:0.1|max:1.0',
            'start_date'         => 'sometimes|date',
            'target_date'        => 'sometimes|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'goal_type.in'                => 'Loại mục tiêu không hợp lệ (0=cutting, 1=bulking, 2=maintaining).',
            'start_weight.min'            => 'Cân nặng tối thiểu là 30 kg.',
            'start_weight.max'            => 'Cân nặng tối đa là 300 kg.',
            'target_weight.min'           => 'Cân nặng mục tiêu tối thiểu là 30 kg.',
            'target_weight.max'           => 'Cân nặng mục tiêu tối đa là 300 kg.',
            'weekly_change_rate.max'      => 'Tối đa 1.0 kg/tuần.',
            'target_date.after'           => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ];
    }
}
