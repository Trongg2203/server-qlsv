<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;


class UserService extends BaseService
{

    protected $userProfileRepo;
    protected $user;
    public function __construct(IUserRepository $iUserRepository, IUserProfileRepository $iUserProfileRepository)
    {
        $this->repo = $iUserRepository;
        $this->userProfileRepo = $iUserProfileRepository;
        $this->user = $iUserRepository;
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

    function getAllActive()
    {
        return $this->repo->getAllActive();
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

    public function get()
    {
        return $this->repo->get();
    }

    public function delete($id): bool
    {
        $user = $this->repo->find($id);
        if ($user) {
            return  $this->user->delete($id);
        }

        return false;
    }
}
