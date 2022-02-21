<?php

namespace App\Services;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class PermissionsService
{
    public function index(PermissionRequest $request)
    {
        $permissions = QueryBuilder::for(Permission::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
            ]);

        return $permissions;
    }

    public function create(): Permission
    {
        $permission = new Permission();

        return $permission;
    }

    public function store(PermissionRequest $request): Permission
    {
        DB::beginTransaction();
        try {

            $permission = Permission::create($request->input('data'));

            $collection = collect($request->input('data.roles', []));
            $plucked = $collection->pluck('id');
            $permission->syncRoles($plucked->all());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $permission;
    }

    public function update(PermissionRequest $request, Permission $permission): Permission
    {
        DB::beginTransaction();
        try {

            $permission->update($request->input('data'));

            $collection = collect($request->input('data.roles', []));
            $plucked = $collection->pluck('id');
            $permission->syncRoles($plucked->all());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $permission;
    }

    public function destroy(Permission $permission): Permission
    {
        DB::beginTransaction();
        try {

            $permission->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $permission;
    }
}
