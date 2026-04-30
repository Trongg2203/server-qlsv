<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;

class UpdateUserProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'date_of_birth'  => 'sometimes|date',
            'gender'         => 'sometimes|integer|in:0,1',
            'height'         => 'sometimes|numeric|min:100|max:250',
            'current_weight' => 'sometimes|numeric|min:30|max:300',
            'activity_level' => 'sometimes|integer|min:0|max:4',
        ];
    }

    public function messages(): array
    {
        return [
            'gender.in'             => 'Giới tính không hợp lệ (0 hoặc 1).',
            'height.min'            => 'Chiều cao tối thiểu là 100 cm.',
            'height.max'            => 'Chiều cao tối đa là 250 cm.',
            'current_weight.min'    => 'Cân nặng tối thiểu là 30 kg.',
            'current_weight.max'    => 'Cân nặng tối đa là 300 kg.',
            'activity_level.min'    => 'Mức độ hoạt động tối thiểu là 0.',
            'activity_level.max'    => 'Mức độ hoạt động tối đa là 4.',
        ];
    }
}
