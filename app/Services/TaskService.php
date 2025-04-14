<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\{Support\Facades\Storage, Support\Str};

class TaskService extends BaseService
{
    public function __construct(TaskRepository $repository)
    {
        parent::__construct($repository);
    }

    public function uploadAttachment(?string $base64String): ?string
    {
        if (!$base64String) {
            return null;
        }

        $base64Data = explode(',', $base64String)[1] ?? null;

        if (!$base64Data) {
            return null;
        }

        $decoded = base64_decode($base64Data);
        $fileName = 'attachments/' . Str::uuid() . '.pdf';

        Storage::disk('public')->put($fileName, $decoded);

        return $fileName;
    }
}
