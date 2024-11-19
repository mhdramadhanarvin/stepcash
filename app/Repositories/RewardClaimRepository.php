<?php

namespace App\Repositories;

use App\Models\Reward;
use App\Models\RewardClaim;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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
            return $data->latest()->paginate($this->perPage);
        }
        return $data->latest()->get();
    }

    public function create(Reward $reward, User $user, array $data)
    {
        $data = array_merge($data, ['user_id' => $user->id, 'reward_id' => $reward->id, 'price' => $reward->price]);
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $stepActivity = $this->getById($id);
        return $stepActivity->update($data);
    }

    public function delete($id)
    {
    }

    public function setSearch($keyword)
    {
    }

    public function paginate(Model $model, int $perPage)
    {
        return $model->paginate($perPage);
    }

    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    public function setWhereArg(array $whereArg)
    {
        $this->whereArg = $whereArg ;
    }

    public function setWithRelation(array $relationName)
    {
        $this->withRelation = $relationName;
    }
}
