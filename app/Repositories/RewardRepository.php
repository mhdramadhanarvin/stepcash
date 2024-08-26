<?php

namespace App\Repositories;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Model;

class RewardRepository implements RewardRepositoryInterface
{
    protected $model;
    protected $search;
    protected $perPage;
    protected $whereArg;
    protected $withRelation;

    public function __construct(Reward $model)
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

    public function create(object $object, array $data)
    {
    }

    public function update(array $data, $id)
    {
        $stepActivity = $this->getById($id);
        return $stepActivity->update($data);
    }

    public function decreaseQuantity($id)
    {
        $reward = $this->getById($id);
        return $this->update(['quantity' => $reward->quantity - 1], $id);
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

    // Your repository methods here...
}
