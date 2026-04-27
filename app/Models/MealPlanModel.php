<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlanModel extends Model
{
    protected $table = 'meal_plans';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    const METHOD_HYBRID        = 0;
    const METHOD_CONTENT_BASED = 1;
    const METHOD_COLLABORATIVE = 2;

    const STATUS_ACTIVE    = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_REPLACED  = 2;

    protected $fillable = [
        'id',
        'user_id',
        'goal_id',
        'calorie_calculation_id',
        'plan_name',
        'start_date',
        'end_date',
        'target_calories_per_day',
        'generation_method',
        'status',
        'created_at',
    ];

    protected $casts = [
        'target_calories_per_day' => 'float',
        'generation_method'       => 'integer',
        'status'                  => 'integer',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(MealPlanDetailModel::class, 'meal_plan_id', 'id')
            ->orderBy('day_number')
            ->orderBy('meal_type');
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(UserGoalModel::class, 'goal_id', 'id');
    }

    public function calorieCalculation(): BelongsTo
    {
        return $this->belongsTo(CalorieCalculationModel::class, 'calorie_calculation_id', 'id');
    }
}
