<?php

namespace App\Repositories\CalorieCalculation;

use App\Repositories\IBaseRepository;

interface ICalorieCalculationRepository extends IBaseRepository
{
    public function getLatestByUser(string $userId): ?object;
    public function getLatestByGoal(string $goalId): ?object;
}
