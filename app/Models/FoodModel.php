<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodModel extends Model
{
    protected $table = 'foods';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    const MEAL_TYPE_ANY       = 0;
    const MEAL_TYPE_BREAKFAST = 1;
    const MEAL_TYPE_LUNCH     = 2;
    const MEAL_TYPE_DINNER    = 3;
    const MEAL_TYPE_SNACK     = 4;

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'serving_size',
        'serving_unit',
        'calories',
        'protein',
        'carbs',
        'fat',
        'meal_type',
        'popularity_score',
        'created_at',
    ];

    protected $casts = [
        'serving_size'     => 'float',
        'calories'         => 'float',
        'protein'          => 'float',
        'carbs'            => 'float',
        'fat'              => 'float',
        'meal_type'        => 'integer',
        'popularity_score' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FoodCategoryModel::class, 'category_id', 'id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(FoodRatingModel::class, 'food_id', 'id');
    }
}
