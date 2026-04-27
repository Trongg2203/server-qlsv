<?php

namespace App\Http\Requests\Food;

use App\Http\Requests\BaseRequest;

class StoreFoodRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'category_id'  => 'required|string|exists:food_categories,id',
            'name'         => 'required|string|max:255',
            'serving_size' => 'required|numeric|min:1',
            'serving_unit' => 'nullable|string|max:50',
            'calories'     => 'required|numeric|min:0',
            'protein'      => 'required|numeric|min:0',
            'carbs'        => 'required|numeric|min:0',
            'fat'          => 'required|numeric|min:0',
            'meal_type'    => 'nullable|integer|in:0,1,2,3,4',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists'   => 'Danh mục không tồn tại.',
            'name.required'        => 'Tên món ăn là bắt buộc.',
            'serving_size.required' => 'Khẩu phần là bắt buộc.',
            'calories.required'    => 'Lượng calo là bắt buộc.',
            'protein.required'     => 'Hàm lượng protein là bắt buộc.',
            'carbs.required'       => 'Hàm lượng carbs là bắt buộc.',
            'fat.required'         => 'Hàm lượng fat là bắt buộc.',
            'meal_type.in'         => 'Loại bữa không hợp lệ (0=any, 1=sáng, 2=trưa, 3=tối, 4=snack).',
        ];
    }
}
