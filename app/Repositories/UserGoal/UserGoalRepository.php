<?php

namespace App\Repositories\UserGoal;

use App\Models\UserGoalModel;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserGoalRepository extends BaseRepository implements IUserGoalRepository
{
    protected $model;

    public function __construct(UserGoalModel $model)
    {
        $this->model = $model;
    }

    function createUserGoal($data)
    {
        $user = Auth::user();
        $userGoal = new UserGoalModel();

        if ($user) {
            $userGoal->id = generateRandomString(10);
            $userGoal->user_id = $user->id;
            $userGoal->goal_type = $data['goal_type'];
            $userGoal->start_weight = $data['start_weight'];
            $userGoal->target_weight = $data['target_weight'];
            $userGoal->start_date = $data['start_date'];
            $userGoal->target_date = $data['target_date'];
            $userGoal->created_by = $user->id;
            $userGoal->estimated_weeks = 1;
            $userGoal->weekly_change_rate = 1;
            $userGoal->save();
        }
        return $userGoal;
    }

    function getBySelf()
    {
        $user = Auth::user();
        if ($user) {
            return $this->model =  $this->model->where('user_id', $user->id)->first();
        }
        return null;
    }
}
