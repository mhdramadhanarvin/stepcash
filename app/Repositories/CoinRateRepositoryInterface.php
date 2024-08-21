<?php

namespace App\Repositories;

interface CoinRateRepositoryInterface
{
    public function getById($id);

    public function getAll();

    public function create(object $object, array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function setSearch($keyword);

    // Add your interface methods here...
}
