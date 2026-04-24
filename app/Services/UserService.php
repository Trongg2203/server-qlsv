<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;


class UserService extends BaseService
{

    protected $userProfileRepo;
    public function __construct(IUserRepository $iUserRepository, IUserProfileRepository $iUserProfileRepository)
    {
        $this->repo = $iUserRepository;
        $this->userProfileRepo = $iUserProfileRepository;
    }



    function login($email, $pass)
    {

        $data = $this->repo->findByEmailAndPassword($email, $pass);
        return $data;
    }

    function findByEmail($data)
    {
        $data = $this->repo->findByEmail($data['email']);
        return $data;
    }

    function get()
    {
        return $this->repo->get();
    }

    public function getDetail()
    {
        $user = Auth::user();

        if ($user) {
            return $this->repo->find($user->id);
        }

        return null;
    }

    public function getProfile()
    {
        $user = Auth::user();

        if ($user) {
            return $this->userProfileRepo->getProfile($user->id);
        }

        return null;
    }

    public function changePassword($data)
    {
        return $this->repo->changePassword($data);
    }
}
