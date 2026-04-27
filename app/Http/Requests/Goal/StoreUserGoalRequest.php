<?php

namespace App\Http\Requests\Goal;

use App\Http\Requests\BaseRequest;

class StoreUserGoalRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'goal_type'          => 'required|integer|in:0,1,2',
            'start_weight'       => 'required|numeric|min:30|max:300',
            'target_weight'      => 'required|numeric|min:30|max:300',
            'weekly_change_rate' => 'required|numeric|min:0.1|max:1.0',
            'start_date'         => 'required|date|after_or_equal:today',
            'target_date'        => 'required|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'goal_type.required'          => 'Loại mục tiêu là bắt buộc.',
            'goal_type.in'                => 'Loại mục tiêu không hợp lệ (0=cutting, 1=bulking, 2=maintaining).',
            'start_weight.required'       => 'Cân nặng hiện tại là bắt buộc.',
            'start_weight.min'            => 'Cân nặng tối thiểu là 30 kg.',
            'start_weight.max'            => 'Cân nặng tối đa là 300 kg.',
            'target_weight.required'      => 'Cân nặng mục tiêu là bắt buộc.',
            'weekly_change_rate.required' => 'Tốc độ thay đổi mỗi tuần là bắt buộc.',
            'weekly_change_rate.max'      => 'Tối đa 1.0 kg/tuần.',
            'start_date.after_or_equal'   => 'Ngày bắt đầu phải là hôm nay hoặc tương lai.',
            'target_date.after'           => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ];
    }
}
