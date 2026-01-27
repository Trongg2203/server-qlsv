<?php

namespace App\Repositories\Module;

use App\Models\Module;
use App\Models\ModuleModel;
use App\Repositories\BaseRepository;
use App\Repositories\IBaseRepository;

class ModuleRepository extends BaseRepository implements IModuleRepository
{
    protected $model;

    public function __construct(ModuleModel $model)
    {
        $this->model = $model;
    }

    

}
