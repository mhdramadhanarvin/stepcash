<?php

namespace App\Repositories;

use App\Models\User;

interface TokenRepositoryInterface
{
    public function getById($id);

    public function getAll();

    public function create(User $user, array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function setSearch($keyword);

    // Add your interface methods here...
}
