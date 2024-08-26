<?php

namespace App\Repositories;

use App\Models\Reward;
use App\Models\RewardClaim;
use App\Models\User;

class RewardClaimRepository implements RewardClaimRepositoryInterface
{
    protected $model;
    protected $search;
    protected $perPage;
    protected $whereArg;
    protected $withRelation;

    public function __construct(RewardClaim $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        $data = $this->model;
        if ($this->withRelation != null) {
            $data = $data->with($this->withRelation);
        }
        return $data->find($id);
    }

    public function getAll()
    {
        $data = $this->model;
        if ($this->withRelation != null) {
            $data = $data->with($this->withRelation);
        }
        if ($this->whereArg !== null) {
            $data = $data->where($this->whereArg);
        }
        if ($this->perPage != null) {
            return $data->paginate($this->perPage);
        }
        return $data->get();
    }

    public function create(Reward $reward, User $user, array $data)
    {
        $data = array_merge($data, ['user_id' => $user->id, 'reward_id' => $reward->id, 'price' => $reward->price]);
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
    }

    public function delete($id)
    {
    }

    public function setSearch($keyword)
    {
    }

    // Your repository methods here...
}
