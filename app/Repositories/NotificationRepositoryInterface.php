<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface NotificationRepositoryInterface
{
    public function getById($id);

    public function getAll();

    public function create(User $user, array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function setSearch($keyword);

    public function paginate(Model $model, int $perPage);

    public function setPerPage(int $perPage);

    public function setWhereArg(array $whereArg);

    public function setWithRelation(array $relationName);
}
