<?php

namespace App\Repositories\MealPlanDetail;

use App\Models\MealPlanDetailModel;
use App\Repositories\BaseRepository;

class MealPlanDetailRepository extends BaseRepository implements IMealPlanDetailRepository
{
    protected $model;

    public function __construct(MealPlanDetailModel $model)
    {
        $this->model = $model;
    }

    /**
     * Bulk insert nhiều dòng meal_plan_details cùng lúc.
     */
    public function bulkInsert(array $rows): bool
    {
        return $this->model->insert($rows);
    }
}
