<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UsersService;

class UserController extends Controller
{
    const MODEL_WITH = ['picture', 'roles', 'permissions'];

    function __construct()
    {
        $this->middleware('permission:users.index|users.create|users.show|users.edit|users.destroy', ['only' => ['index']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.show', ['only' => ['show']]);
        $this->middleware('permission:users.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(UserRequest $request, UsersService $service)
    {
        $users = $service->index($request)->with(self::MODEL_WITH);

        $users = match ($request->has('size')) {
            true => $users->paginate($request->query('size')),
            default => $users->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new UserCollection($users))->additional($additional);
    }

    public function create(UsersService $service)
    {
        $user = $service->create();

        $additional = ['collections' => []];

        return (new UserResource($user))->additional($additional);
    }

    public function store(UserRequest $request, UsersService $service)
    {
        $user = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(User $user)
    {
        $additional = ['collections' => []];

        dd($user->getPermissionsViaRoles()->toArray());

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(User $user)
    {
        $additional = ['collections' => []];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function update(UserRequest $request, User $user, UsersService $service)
    {
        $user = $service->update($request, $user);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(User $user, UsersService $service)
    {
        $user = $service->destroy($user);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new UserResource($user))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
