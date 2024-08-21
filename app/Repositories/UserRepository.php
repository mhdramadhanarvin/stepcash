<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected $model;
    protected $search;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByGoogleId($google_id)
    {
        return $this->model->where('google_id', $google_id)->first();
    }

    public function getAll()
    {
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {

    }

    public function addCoin(User $user, $coin)
    {
        $user = $this->getById($user->id);
        return $user->update([
            'coin' => $user->coin + $coin
        ]);
    }

    public function cutCoin(User $user, $coin)
    {
        $user = $this->getById($user->id);
        return $user->update([
            'coin' => $user->coin - $coin
        ]);
    }

    public function delete($id)
    {
    }

    public function setSearch($keyword)
    {
    }

    // Your repository methods here...
}
