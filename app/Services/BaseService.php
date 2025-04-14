<?php

namespace App\Services;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

abstract class BaseService
{
    protected $repository;

    public function __construct(object $repository)
    {
        $this->repository = $repository;
    }

    public function index(array $filters = [], int $paginate = 24, array $relations = [], array $withCount = [], array $columns = ['*'], array $scopes = []): mixed
    {
        $model = $this->repository->getModel()->with($relations)->withCount($withCount);

        if ($columns !== ['*']) {
            $model = $model->addSelect($columns);
        }

        foreach ($scopes as $scope) {
            $model = $scope($model);
        }

        return app(Pipeline::class)->send($model)->through($filters)->thenReturn()->paginate($paginate);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function insert(array $data): bool
    {
        return $this->repository->insert($data);
    }

    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function update(array $data, int $id): Model
    {
        return $this->repository->update($data, $id);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    public function getAll(array $filters = [], $relations = [], int $limit = null, array $withCount = [], array $columns = ['*'], array $scopes = []): mixed
    {
        $model = $this->repository->getModel()->with($relations)->withCount($withCount);
        if ($columns !== ['*']) {
            $model = $model->addSelect($columns);
        }

        foreach ($scopes as $scope) {
            $model = $scope($model);
        }

        $model = app(Pipeline::class)->send($model)->through($filters)->thenReturn();

        if ($limit > 0) {
            $model = $model->limit($limit);
        }

        return $model->get();
    }

    public function list(array $filters = [], array $relations = [], array $withCount = []): JsonResponse
    {
        $query = $this->repository->getModel()->with($relations)->withCount($withCount);

        return DataTables::of(app(Pipeline::class)->send($query)->through($filters)->thenReturn())->toJson();
    }

    public function select(array $filters = [], string $column='name', int $limit = 10, array $extraColumns = []): mixed
    {
        $model = $this->repository->getModel()->newQuery();
        $data = app(Pipeline::class)
            ->send($model)
            ->through($filters)
            ->thenReturn()
            ->take($limit)
            ->get();


        $arr = [];
        foreach ($data as $item) {
            $result = [
                'id' => $item->id,
                'text' => $item->{$column},
            ];

            foreach ($extraColumns as $extraColumn) {
                $result[$extraColumn] = $item->{$extraColumn} ?? null;
            }

            $arr[] = $result;
        }
        return $arr;
    }

    /**
     * @param Model $model
     * @param array $imageColumns
     * @return void
     */
    public function handleDeleteWithImages(Model $model, array $imageColumns = []): void
    {
        foreach ($imageColumns as $imageColumn) {
            $filePath = $model->{$imageColumn};

            if (filter_var($filePath, FILTER_VALIDATE_URL)) {
                $parsedUrl = parse_url($filePath);
                $filePath = str_replace([asset('storage'),'/storage/','/public'], '', $parsedUrl['path']);
            }
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
        $model->delete();
    }
}
