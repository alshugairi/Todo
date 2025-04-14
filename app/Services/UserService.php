<?php

namespace App\Services;

use App\Helpers\DesignHelper;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\{Database\Eloquent\Model,
    Http\JsonResponse,
    Http\Request,
    Pipeline\Pipeline,
    Support\Arr,
    Support\Str,
    Support\Facades\Hash,
    Support\Facades\Storage};
use Yajra\DataTables\DataTables;
use Exception;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create(data: $this->excludeItem(data: $data));
        if (isset($data['categories'])) {
            $user->categories()->sync($data['categories']);
        }
        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }
        return $user;
    }

    public function update(array $data, int $id): Model
    {
        $user = $this->repository->update(data: $this->excludeItem(data: $this->checkPassword(data: $data)), id: $id);
        if (isset($data['categories'])) {
            $user->categories()->sync($data['categories']);
        }
        if (isset($data['role'])) {
           $user->assignRole($data['role']);
        }
        return $user;
    }

    public function storePhoto(array $data): array
    {
        if (!empty($data['avatar'])) {
            $filePath = $data['avatar']->store('user', 'public');
            $data['avatar'] = asset(Storage::url($filePath));
        } else {
            $data = Arr::except($data, ['avatar']);
        }
        return $data;
    }

    private function checkPassword(array $data): array
    {
        if (empty($data['password'])) {
            $data = $this->excludeItem(data: $data, key: 'password');
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    private function excludeItem(array $data, array|string $key = 'role'): array
    {
        return Arr::except(array: $data, keys: $key);
    }

    public function list(array $filters = [], array $relations = [], array $withCount = []): JsonResponse
    {
        $query = $this->repository->getModel()->with($relations);

        return DataTables::of(app(Pipeline::class)->send($query)->through($filters)->thenReturn())
            ->addColumn('name', function ($user) { return $user->first_name .' '. $user->last_name; })
            ->addColumn('role', function ($user) { return $user->roles ? $user->roles->value('name') : ''; })
            ->editColumn('status', function ($user) { return DesignHelper::renderStatus($user->status); })
            ->rawColumns(['status'])
            ->toJson();
    }

    public function revokeAndCreateToken(User $user): string
    {
        $this->revokeTokens(user: $user);
        return $this->createToken(user: $user);
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
    }

    public function createToken(User $user): string
    {
        return $user->createToken(name: User::TokenName)->plainTextToken;
    }

    public function getUserByEmail(string $email): ?Model
    {
        return $this->repository->getModel()->newQuery()
            ->where(column: 'email', operator: '=', value: $email)
            ->first();
    }
}
