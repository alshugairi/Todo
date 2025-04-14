<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function update(array $data,int $id): Model
    {
        $model = $this->model->find($id);

        if ($model->update($data)) {
            return $model;
        }
        throw new \Exception('Failed to update the model');
    }

    public function delete(int $id): bool
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }
}
