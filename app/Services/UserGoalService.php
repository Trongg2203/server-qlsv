<?php

namespace App\Services;

use App\Models\UserGoalModel;
use App\Repositories\UserGoal\IUserGoalRepository;

class UserGoalService extends BaseService
{
    public function __construct(IUserGoalRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getMyGoals(): array
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getByUser($userId);
    }

    public function getActiveGoal(): ?object
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getActiveGoalByUser($userId);
    }

    public function createGoal(array $data): object
    {
        $userId = auth()->guard('api')->id();

        // Cancel any currently active goal before creating a new one
        $activeGoal = $this->repo->getActiveGoalByUser($userId);
        if ($activeGoal) {
            $this->repo->update($activeGoal->id, ['status' => UserGoalModel::STATUS_CANCELLED]);
        }

        $data['id']      = generateRandomString();
        $data['user_id'] = $userId;
        $data['status']  = UserGoalModel::STATUS_ACTIVE;

        return $this->repo->create($data);
    }

    public function updateGoal(array $data): object
    {
        return $this->repo->update($data['id'], $data);
    }
}
