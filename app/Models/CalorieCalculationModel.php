<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalorieCalculationModel extends Model
{
    protected $table = 'calorie_calculations';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    const MACRO_CUTTING    = 0; // 40% protein / 30% carbs / 30% fat
    const MACRO_BULKING    = 1; // 30% / 45% / 25%
    const MACRO_MAINTAINING = 2; // 30% / 40% / 30%

    protected $fillable = [
        'id',
        'user_id',
        'goal_id',
        'bmr',
        'tdee',
        'target_calories',
        'protein_grams',
        'carbs_grams',
        'fat_grams',
        'macro_ratio',
        'valid_from',
        'created_at',
    ];

    protected $casts = [
        'bmr'             => 'float',
        'tdee'            => 'float',
        'target_calories' => 'float',
        'protein_grams'   => 'float',
        'carbs_grams'     => 'float',
        'fat_grams'       => 'float',
        'macro_ratio'     => 'integer',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(UserGoalModel::class, 'goal_id', 'id');
    }
}
