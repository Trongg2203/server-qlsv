<?php

namespace App\Repositories\ModuleAction;

use App\Models\ModuleActionModel;
use App\Repositories\BaseRepository;
use Illuminate\Validation\ValidationException;

class ModuleActionRepository extends BaseRepository implements IModuleActionRepository
{
    protected $model;

    public function __construct(ModuleActionModel $model)
    {
        $this->model = $model;
    }

    public function findCode($code)
    {
        return $this->model->where('code', $code)->exists();
    }

    public function createModuleAction($data)
    {
        $model['id'] = generateRandomString(10);
        $model['name'] = $data['name'];
        $model['code'] = $data['code'];
        $model['description'] = $data['description'];
        $model['module_id'] = $data['module_id'] ?? '';
        $model['status'] = $data['status'] ?? 1;


        $moduleAction = $this->model->create($model);
        return $moduleAction;
    }
}
