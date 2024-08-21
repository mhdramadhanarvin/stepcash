<?php

namespace App\Repositories;

use App\Models\Partner;

class PartnerRepository implements PartnerRepositoryInterface
{
    protected $model, $search;

    public function __construct(Partner $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {}

    public function getAll()
    {}

    public function create(object $object, array $data)
    {}

    public function update(array $data, $id)
    {}

    public function delete($id)
    {}

    public function setSearch($keyword)
    {}

    // Your repository methods here...
}
