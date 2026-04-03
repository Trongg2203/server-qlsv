<?php

namespace App\Services;

use App\Repositories\ModuleAction\IModuleActionRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Request;

class ModuleActionService extends BaseService
{

    public function __construct(IModuleActionRepository $iModuleActionRepository)
    {
        $this->repo = $iModuleActionRepository;
    }


    public function createModuleAction($data)
    {
        return $this->repo->createModuleAction($data);
    }
}
