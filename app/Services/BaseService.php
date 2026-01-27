<?php

namespace App\Services;

abstract class BaseService
{
    protected $repo;
    public const CATEGORY_BLOG = 1;
    public const ACTIVE = 1;
    public const UNACTIVE = 0;


    public function get()
    {
        $model = $this->repo->get();
        return $model;
    }

    public function getAll()
    {
        $query = $this->repo->getAll();
        return $query;
    }

    public function detail($id)
    {
        $query = $this->repo->find($id);
        return $query;
    }

    public function getById($id)
    {
        return $this->repo->getById($id);
    }

    public function create($data)
    {
        $keyType = $this->repo->getModel()->getKeyType();
        $primaryKey = $this->repo->getModel()->getKeyName();

        if ($keyType == 'string' && (empty($data[$primaryKey]) || !isset($data[$primaryKey])))
            $data[$primaryKey] = generateRandomString();
        $data['created_by'] = auth()->guard()->id() ?? "0000000000";
        $data['updated_by'] = $data['created_by'];

        return $this->repo->create($data);
    }

    public function update($data)
    {
        $data['updated_by'] = auth()->guard()->id();

        return $this->repo->update($data['id'], $data);
    }

    public function delete($id)
    {
        $query = $this->repo->delete($id);
        return $query;
    }
}
