<?php

namespace App\Http\Requests\UserProfile;

use App\Http\Requests\BaseRequest;

class StoreUserProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|integer|in:0,1',
            'height'         => 'required|numeric|min:100|max:250',
            'current_weight' => 'required|numeric|min:30|max:300',
            'activity_level' => 'sometimes|integer|min:0|max:4',
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.required'   => 'Ngày sinh là bắt buộc.',
            'gender.required'          => 'Giới tính là bắt buộc.',
            'gender.in'                => 'Giới tính không hợp lệ (0 hoặc 1).',
            'height.required'          => 'Chiều cao là bắt buộc.',
            'height.min'               => 'Chiều cao tối thiểu là 100 cm.',
            'height.max'               => 'Chiều cao tối đa là 250 cm.',
            'current_weight.required'  => 'Cân nặng hiện tại là bắt buộc.',
            'current_weight.min'       => 'Cân nặng tối thiểu là 30 kg.',
            'current_weight.max'       => 'Cân nặng tối đa là 300 kg.',
            'activity_level.min'       => 'Mức độ hoạt động tối thiểu là 0.',
            'activity_level.max'       => 'Mức độ hoạt động tối đa là 4.',
        ];
    }
}
