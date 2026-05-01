<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_of_birth'  => 'sometimes|date|before:-10 years',
            'gender'         => 'sometimes|integer|in:0,1',
            'height'         => 'sometimes|numeric|min:100|max:250',
            'current_weight' => 'sometimes|numeric|min:30|max:300',
            'activity_level' => 'sometimes|integer|in:0,1,2,3,4',
        ];
    }

    public function messages(): array
    {
        return [
            'gender.in'         => 'Giới tính không hợp lệ (0=Nam, 1=Nữ).',
            'height.min'        => 'Chiều cao tối thiểu 100 cm.',
            'current_weight.min' => 'Cân nặng tối thiểu 30 kg.',
            'activity_level.in' => 'Mức độ vận động không hợp lệ (0-4).',
        ];
    }
}
