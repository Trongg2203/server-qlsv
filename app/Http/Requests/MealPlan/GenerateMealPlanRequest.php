<?php

namespace App\Http\Requests\MealPlan;

use App\Http\Requests\BaseRequest;

class GenerateMealPlanRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'allergens'       => 'nullable|array',
            'allergens.*'     => 'string|max:100',
            'disliked_foods'  => 'nullable|array',
            'disliked_foods.*' => 'string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'allergens.array'      => 'Danh sách dị ứng phải là mảng.',
            'disliked_foods.array' => 'Danh sách món không thích phải là mảng.',
        ];
    }
}
