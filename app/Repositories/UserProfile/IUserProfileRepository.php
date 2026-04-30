<?php

namespace App\Repositories\UserProfile;

use App\Repositories\IBaseRepository;

interface IUserProfileRepository extends IBaseRepository
{
    public function getMyProfile();
    public function updateMyProfile(array $attributes);
    public function createMyProfile(array $attributes);
}
