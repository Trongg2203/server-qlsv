<?php

namespace App\Repositories\UserProfile;

use App\Repositories\IBaseRepository;

interface IUserProfileRepository extends IBaseRepository
{
    public  function getProfile($user_id);
}
