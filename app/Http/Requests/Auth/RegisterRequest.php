<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'password'       => 'required|string|min:6|max:50',
            'date_of_birth'  => 'required|date|before:-10 years',
            'gender'         => 'required|integer|in:0,1',
            'height'         => 'required|numeric|min:100|max:250',
            'weight'         => 'required|numeric|min:30|max:300',
            'activity_level' => 'required|integer|in:0,1,2,3,4',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Họ tên là bắt buộc.',
            'email.required'         => 'Email là bắt buộc.',
            'email.unique'           => 'Email đã được đăng ký.',
            'password.required'      => 'Mật khẩu là bắt buộc.',
            'password.min'           => 'Mật khẩu tối thiểu 6 ký tự.',
            'date_of_birth.required' => 'Ngày sinh là bắt buộc.',
            'date_of_birth.before'   => 'Tuổi tối thiểu là 10.',
            'gender.required'        => 'Giới tính là bắt buộc.',
            'gender.in'              => 'Giới tính không hợp lệ (0=Nam, 1=Nữ).',
            'height.required'        => 'Chiều cao là bắt buộc.',
            'height.min'             => 'Chiều cao tối thiểu 100 cm.',
            'weight.required'        => 'Cân nặng là bắt buộc.',
            'weight.min'             => 'Cân nặng tối thiểu 30 kg.',
            'activity_level.in'      => 'Mức độ vận động không hợp lệ (0-4).',
        ];
    }
}
