<?php

namespace App\Repositories\UserGoal;

use App\Models\UserGoalModel;
use App\Repositories\BaseRepository;

class UserGoalRepository extends BaseRepository implements IUserGoalRepository
{
    protected $model;

    public function __construct(UserGoalModel $model)
    {
        $this->model = $model;
    }

    public function getActiveGoalByUser(string $userId): ?object
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', UserGoalModel::STATUS_ACTIVE)
            ->latest('created_at')
            ->first();
    }

    public function getByUser(string $userId): array
    {
        $data = $this->model
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }
}
