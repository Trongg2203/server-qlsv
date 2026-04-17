<?php

namespace App\Services;

use App\Repositories\UserGoal\IUserGoalRepository;
use App\Services\BaseService;

class UserGoalService extends BaseService
{

    public function __construct(IUserGoalRepository $iUserGoalRepository)
    {
        $this->repo = $iUserGoalRepository;
    }

    function createUserGoal($data)
    {
        return $this->repo->createUserGoal($data);
    }

    function getBySelf()
    {
        return $this->repo->getBySelf();
    }
}
