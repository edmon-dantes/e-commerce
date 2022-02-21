<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Services\PermissionsService;

class PermissionController extends Controller
{
    const MODEL_WITH = ['roles'];

    function __construct()
    {
        $this->middleware('permission:permissions.index|permissions.create|permissions.show|permissions.edit|permissions.destroy', ['only' => ['index']]);
        $this->middleware('permission:permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permissions.show', ['only' => ['show']]);
        $this->middleware('permission:permissions.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(PermissionRequest $request, PermissionsService $service)
    {
        $permissions = $service->index($request)->with(self::MODEL_WITH);

        $permissions = match ($request->has('size')) {
            true => $permissions->paginate($request->query('size')),
            default => $permissions->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new PermissionCollection($permissions))->additional($additional);
    }

    public function create(PermissionsService $service)
    {
        $permission = $service->create();

        $additional = ['collections' => []];

        return (new PermissionResource($permission))->additional($additional);
    }

    public function store(PermissionRequest $request, PermissionsService $service)
    {
        $permission = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new PermissionResource($permission->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Permission $permission)
    {
        $additional = ['collections' => []];

        dd($permission->getPermissionsViaRoles()->toArray());

        return (new PermissionResource($permission->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Permission $permission)
    {
        $additional = ['collections' => []];

        return (new PermissionResource($permission->load(self::MODEL_WITH)))->additional($additional);
    }

    public function update(PermissionRequest $request, Permission $permission, PermissionsService $service)
    {
        $permission = $service->update($request, $permission);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new PermissionResource($permission->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Permission $permission, PermissionsService $service)
    {
        $permission = $service->destroy($permission);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new PermissionResource($permission))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
