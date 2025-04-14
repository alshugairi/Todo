<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface BaseRepositoryInterface
{
    public function getAll();

    public function find(int $id): ?Model;

    public function create(array $data): Model;

    public function insert(array $data): bool;

    public function update(array $data, int $id): Model;

    public function delete(int $id): bool;

    public function all();

    public function getModel(): Model;
    public function newQuery(): Builder;
}
