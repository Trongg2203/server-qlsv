<?php

namespace App\Repositories\MealPlan;

use App\Models\MealPlanModel;
use App\Repositories\BaseRepository;

class MealPlanRepository extends BaseRepository implements IMealPlanRepository
{
    protected $model;

    public function __construct(MealPlanModel $model)
    {
        $this->model = $model;
    }

    public function getByUser(string $userId): array
    {
        $data = $this->model
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    public function getByIdAndUser(string $id, string $userId): ?object
    {
        return $this->model
            ->with(['details.food:id,name,serving_size,serving_unit,calories,protein,carbs,fat'])
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function getActiveByUser(string $userId): ?object
    {
        return $this->model
            ->with(['details.food:id,name,serving_size,serving_unit,calories,protein,carbs,fat'])
            ->where('user_id', $userId)
            ->where('status', MealPlanModel::STATUS_ACTIVE)
            ->latest('created_at')
            ->first();
    }

    public function replaceOldPlans(string $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->where('status', MealPlanModel::STATUS_ACTIVE)
            ->update(['status' => MealPlanModel::STATUS_REPLACED]);
    }
}
