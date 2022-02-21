<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RolesService;

class RoleController extends Controller
{
    const MODEL_WITH = ['permissions'];

    function __construct()
    {
        $this->middleware('permission:roles.index|roles.create|roles.show|roles.edit|roles.destroy', ['only' => ['index']]);
        $this->middleware('permission:roles.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles.show', ['only' => ['show']]);
        $this->middleware('permission:roles.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(RoleRequest $request, RolesService $service)
    {
        $roles = $service->index($request)->with(self::MODEL_WITH);

        $roles = match ($request->has('size')) {
            true => $roles->paginate($request->query('size')),
            default => $roles->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new RoleCollection($roles))->additional($additional);
    }

    public function create(RolesService $service)
    {
        $role = $service->create();

        $additional = ['collections' => []];

        return (new RoleResource($role))->additional($additional);
    }

    public function store(RoleRequest $request, RolesService $service)
    {
        $role = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new RoleResource($role->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Role $role)
    {
        $additional = ['collections' => []];

        dd($role->getRolesViaRoles()->toArray());

        return (new RoleResource($role->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Role $role)
    {
        $additional = ['collections' => []];

        return (new RoleResource($role->load(self::MODEL_WITH)))->additional($additional);
    }

    public function update(RoleRequest $request, Role $role, RolesService $service)
    {
        $role = $service->update($request, $role);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new RoleResource($role->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Role $role, RolesService $service)
    {
        $role = $service->destroy($role);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new RoleResource($role))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
