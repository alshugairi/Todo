<?php

namespace App\Http\Controllers\Api;

use App\{Http\Controllers\Controller,
    Http\Requests\Api\TaskRequest,
    Http\Resources\TaskResource,
    Models\Task,
    Pipelines\TaskFilterPipeline,
    Services\TaskService,
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

    public function update(TaskRequest $request, Task $task): Response
    {
        return Response::response(
            message: __(key:'share.request_successfully'),
            data: $this->service->update(data: $request->validated(), id: $task->id)
        );
    }
}
