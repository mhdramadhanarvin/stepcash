<?php

namespace App\Repositories;

use App\Models\CoinRate;

class CoinRateRepository implements CoinRateRepositoryInterface
{
    protected $model;
    protected $search;

    public function __construct(CoinRate $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAll()
    {
    }

    public function create(object $object, array $data)
    {
    }

    public function update(array $data, $id)
    {
        $notification = $this->getById($id);
        return $notification->update($data);
    }

    public function delete($id)
    {
    }

    public function setSearch($keyword)
    {
    }

    // Your repository methods here...
}
