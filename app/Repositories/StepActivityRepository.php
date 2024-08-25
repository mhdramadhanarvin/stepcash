<?php

namespace App\Repositories;

use App\Models\StepActivity;
use App\Models\User;
use Carbon\Carbon;

class StepActivityRepository implements StepActivityRepositoryInterface
{
    protected $model;
    protected $search;

    public function __construct(StepActivity $model)
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

    public function getInToday($user_id)
    {
        return $this->model->where('user_id', $user_id)->whereDate('created_at', Carbon::today())->first();
    }

    public function getAllTodayNotConvert()
    {
        return $this->model->where('is_convert', 0)->whereDate('created_at', Carbon::today())->get();
    }

    public function create(User $user, array $data)
    {
        $data = array_merge($data, ['user_id' => $user->id]);
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

    // Your repository methods here...
}
