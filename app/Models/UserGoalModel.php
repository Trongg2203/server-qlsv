<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGoalModel extends Model
{
    protected $table = 'user_goals';
    public $timestamps = true;
    protected $keyType = 'string';
    public $incrementing = false;

    const GOAL_CUTTING    = 0;
    const GOAL_BULKING    = 1;
    const GOAL_MAINTAINING = 2;

    const STATUS_ACTIVE    = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;

    protected $fillable = [
        'id',
        'user_id',
        'goal_type',
        'start_weight',
        'target_weight',
        'weekly_change_rate',
        'start_date',
        'target_date',
        'status',
    ];

    protected $casts = [
        'goal_type'          => 'integer',
        'status'             => 'integer',
        'start_weight'       => 'float',
        'target_weight'      => 'float',
        'weekly_change_rate' => 'float',
    ];

    public function getGoalTypeNameAttribute(): string
    {
        return match ($this->goal_type) {
            self::GOAL_CUTTING    => 'Cutting',
            self::GOAL_BULKING    => 'Bulking',
            self::GOAL_MAINTAINING => 'Maintaining',
            default               => 'Unknown',
        };
    }

    public function calorieCalculations(): HasMany
    {
        return $this->hasMany(CalorieCalculationModel::class, 'goal_id', 'id');
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlanModel::class, 'goal_id', 'id');
    }
}
