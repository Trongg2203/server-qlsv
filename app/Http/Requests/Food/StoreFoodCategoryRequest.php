<?php

namespace App\Http\Requests\Food;

use App\Http\Requests\BaseRequest;

class StoreFoodCategoryRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:food_categories,name',
            'description' => 'nullable|string|max:1000',
            'sort_order'  => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique'   => 'Tên danh mục đã tồn tại.',
        ];
    }
}
