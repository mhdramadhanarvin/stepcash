<?php

namespace App\Repositories;

use App\Models\Token;
use App\Models\User;

class TokenRepository implements TokenRepositoryInterface
{
    protected $model;
    protected $search;

    public function __construct(Token $model)
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

    public function create(User $user, array $data)
    {
        $data = array_merge($data, ['user_id' => $user->id]);
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $token = $this->getById($id);
        return $token->update($data);
    }

    public function delete($id)
    {
    }

    public function setSearch($keyword)
    {
    }

    // Your repository methods here...
}
