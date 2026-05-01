<?php

namespace App\Repositories\CalorieCalculation;

use App\Models\CalorieCalculationModel;
use App\Repositories\BaseRepository;

class CalorieCalculationRepository extends BaseRepository implements ICalorieCalculationRepository
{
    protected $model;

    public function __construct(CalorieCalculationModel $model)
    {
        $this->model = $model;
    }

    public function getLatestByUser(string $userId): ?object
    {
        return $this->model
            ->where('user_id', $userId)
            ->latest('created_at')
            ->first();
    }

    public function getLatestByGoal(string $goalId): ?object
    {
        return $this->model
            ->where('goal_id', $goalId)
            ->latest('created_at')
            ->first();
    }

    public function getHistoryByUser(string $userId): array
    {
        $data = $this->model
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
        return ['total' => $data->count(), 'data' => $data];
    }
}
