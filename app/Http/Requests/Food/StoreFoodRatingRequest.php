<?php

namespace App\Http\Requests\Food;

use App\Http\Requests\BaseRequest;

class StoreFoodRatingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'food_id' => 'required|string|exists:foods,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'food_id.required' => 'Món ăn là bắt buộc.',
            'food_id.exists'   => 'Món ăn không tồn tại.',
            'rating.required'  => 'Đánh giá là bắt buộc.',
            'rating.min'       => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max'       => 'Đánh giá tối đa là 5 sao.',
        ];
    }
}
