<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Services\BaseService;


class UserService extends BaseService
{

    public function __construct(IUserRepository $iUserRepository)
    {
        $this->repo = $iUserRepository;
    }



    function login($email, $pass)
    {

        $data = $this->repo->findByEmail($email);
        return $data;
    }

    function get()
    {
        return $this->repo->get();
    }
}
