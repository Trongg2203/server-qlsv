<?php


namespace App\Repositories\UserProfile;

use App\Models\UserProfileModel;
use App\Repositories\BaseRepository;
use App\Repositories\UserProfile\IUserProfileRepository;

class UserProfileRepository extends BaseRepository implements IUserProfileRepository
{

    protected $model;

    public function __construct(UserProfileModel $model)
    {
        $this->model = $model;
    }

    public function getProfile($user_id)
    {
        if (!$user_id) return;

        return $this->model
            ->with('user')
            ->where('user_id', $user_id)
            ->first();
    }
}
