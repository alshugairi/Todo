<?php

namespace App\Http\Controllers\Api;

use App\{Http\Controllers\Controller,
    Http\Requests\Api\TaskRequest,
    Http\Resources\TaskResource,
    Models\Task,
    Pipelines\SortFilterPipeline,
    Pipelines\TaskFilterPipeline,
    Services\TaskService,
    Utils\HttpFoundation\HttpStatus,
    Utils\HttpFoundation\Response};

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $service)
    {
    }

    public function index(): Response
    {
        return Response::response(
            message: __(key:'share.request_successfully'),
            data: TaskResource::collection($this->service->index(filters: [
                new SortFilterPipeline(sortByColumn: 'id', sortType: 'desc'),
                new TaskFilterPipeline(request: request()->merge(['user_id' => auth()->id()])),
            ]))
        );
    }

    public function store(TaskRequest $request): Response
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        return Response::response(
            message: __(key:'share.request_successfully'),
            data: $this->service->create(data: $data),
        );
    }

    public function destroy(Task $task): Response
    {
        if ($task->user_id !== auth()->id()) {
            return Response::error(
                message: __(key:'share.unauthorized'),
                status: HttpStatus::HTTP_UNAUTHORIZED
            );
        }
        $this->service->delete(id: $task->id);
        return Response::response(
            message: __(key:'share.deleted_successfully'),
        );
    }

    public function update(TaskRequest $request, Task $task): Response
    {
        if ($task->user_id !== auth()->id()) {
            return Response::error(
                message: __(key:'share.unauthorized'),
                status: HttpStatus::HTTP_UNAUTHORIZED
            );
        }
        $this->service->update(data: $request->validated(), id: $task->id);

        return Response::response(
            message: __(key:'share.updated_successfully'),
        );
    }
}
