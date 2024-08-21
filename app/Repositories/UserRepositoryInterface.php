<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getById($id);

    public function getByGoogleId($google_id);

    public function getAll();

    public function create(array $data);

    public function update(array $data, $id);

    public function addCoin(User $user, $coin);

    public function cutCoin(User $user, $coin);

    public function delete($id);

    public function setSearch($keyword);

    // Add your interface methods here...
}
