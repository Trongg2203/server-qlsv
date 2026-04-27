<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlanDetailModel extends Model
{
    protected $table = 'meal_plan_details';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    const MEAL_TYPE_BREAKFAST = 1;
    const MEAL_TYPE_LUNCH     = 2;
    const MEAL_TYPE_DINNER    = 3;
    const MEAL_TYPE_SNACK     = 4;

    protected $fillable = [
        'id',
        'meal_plan_id',
        'food_id',
        'day_number',
        'meal_type',
        'servings',
        'total_calories',
        'total_protein',
        'total_carbs',
        'total_fat',
    ];

    protected $casts = [
        'day_number'     => 'integer',
        'meal_type'      => 'integer',
        'servings'       => 'float',
        'total_calories' => 'float',
        'total_protein'  => 'float',
        'total_carbs'    => 'float',
        'total_fat'      => 'float',
    ];

    public function food(): BelongsTo
    {
        return $this->belongsTo(FoodModel::class, 'food_id', 'id');
    }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlanModel::class, 'meal_plan_id', 'id');
    }
}
