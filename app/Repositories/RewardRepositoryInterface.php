<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RewardRepositoryInterface
{
    public function getById($id);

    public function getAll();

    public function create(object $object, array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function setSearch($keyword);

    public function paginate(Model $model, int $perPage);

    public function setPerPage(int $perPage);

    public function setWhereArg(array $whereArg);

    public function setWithRelation(array $relationName);
    // Add your interface methods here...
}
