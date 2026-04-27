<?php

namespace App\Repositories\MealPlan;

use App\Repositories\IBaseRepository;

interface IMealPlanRepository extends IBaseRepository
{
    public function getByUser(string $userId): array;
    public function getActiveByUser(string $userId): ?object;
    public function replaceOldPlans(string $userId): void;
}
