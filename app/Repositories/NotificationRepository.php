<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $model;
    protected $search;
    protected $perPage;
    protected $whereArg;
    protected $withRelation;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
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

    public function create(User $user, array $data)
    {
        $data = array_merge($data, ['user_id' => $user->id]);
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $notification = $this->getById($id);
        return $notification->update($data);
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
