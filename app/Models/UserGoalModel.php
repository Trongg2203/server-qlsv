<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGoalModel extends BaseModel
{

    protected $table = 'user_goals';
    protected $fillable = [
        'id',
        'user_id',
        'goal_type',
        'start_weight',
        'target_weight',
        'target_bmi',
        'weekly_change_rate',
        'estimated_weeks',
        'start_date',
        'target_date',
        'is_active',
        'is_completed',
        'completed_at',
        'status',

    ];

    protected $casts = [
        'goal_type' => 'int',
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
        'status' => 'int'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
