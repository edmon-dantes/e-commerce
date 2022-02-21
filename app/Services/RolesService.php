<?php

namespace App\Services;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class RolesService
{
    public function index(RoleRequest $request)
    {
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
            ]);

        return $roles;
    }

    public function create(): Role
    {
        $role = new Role();

        return $role;
    }

    public function store(RoleRequest $request): Role
    {
        DB::beginTransaction();
        try {

            $role = Role::create($request->input('data'));

            $collection = collect($request->input('data.permissions', []));
            $plucked = $collection->pluck('id');
            $role->syncPermissions($plucked->all());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $role;
    }

    public function update(RoleRequest $request, Role $role): Role
    {
        DB::beginTransaction();
        try {

            $role->update($request->input('data'));

            $collection = collect($request->input('data.permissions', []));
            $plucked = $collection->pluck('id');
            $role->syncPermissions($plucked->all());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $role;
    }

    public function destroy(Role $role): Role
    {
        DB::beginTransaction();
        try {

            $role->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $role;
    }
}
