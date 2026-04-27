<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileModel extends BaseModel
{
    protected $table = 'user_profiles';
    protected $fillable = [
        'id',
        'user_id',
        'date_of_birth',
        'gender',
        'height',
        'current_weight',
        'bmi',
        'bmi_category',
        'activity_level'
    ];

    protected $casts = [
        'gender' => 'integer',
        'activity_level' => 'integer',
    ];

    public function getGenderNameAttribute(): string
    {
        return match ($this->gender) {
            0 => 'Male',
            1 => 'Female',
            2 => 'Other',
            default => 'Unknown'
        };
    }

    public function getActivityLevelNameAttribute(): string
    {
        return match ($this->activity_level) {
            0 => 'Sedentary',
            1 => 'Lightly Active',
            2 => 'Moderately Active',
            3 => 'Very Active',
            4 => 'Extremely Active',
            default => 'Unknown'
        };
    }


    /**
     * Quan hệ ngược: Profile thuộc về User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select(
                'id',
                'name',
                'email',
                'phone',
                'role',
                'account_status',
            );
    }
}
