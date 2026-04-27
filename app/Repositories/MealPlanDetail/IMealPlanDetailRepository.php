<?php

namespace App\Repositories\MealPlanDetail;

use App\Repositories\IBaseRepository;

interface IMealPlanDetailRepository extends IBaseRepository
{
    public function bulkInsert(array $rows): bool;
}
