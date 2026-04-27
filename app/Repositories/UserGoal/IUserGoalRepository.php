<?php

namespace App\Repositories\UserGoal;

use App\Repositories\IBaseRepository;

interface IUserGoalRepository extends IBaseRepository
{
   
    public function getActiveGoalByUser(string $userId): ?object;
    public function getByUser(string $userId): array;
}
