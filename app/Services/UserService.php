<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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

    public function createUser($data)
    {
        if (!isset($data['password'])) {
            return null;
        }

        $data['password'] = Hash::make($data['password']);

        return $this->repo->create($data);
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
