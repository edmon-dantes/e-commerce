<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\RoleRequest;
use App\Http\Resources\Permission\RoleCollection;
use App\Http\Resources\Permission\RoleResource;
use App\Models\Permission\RoleSpatie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = RoleSpatie::with('permissions')
            ->search($request->input('_search'))
            ->sort($request->input('_sort'))
            ->paginate($request->input('_size'));

        return (new RoleCollection($roles))->additional([
            'meta' => [
                'collections' => (object)[]
            ]
        ]);
    }

    public function create()
    {
        $role = new RoleSpatie;
        return $this->responseRole($role);
    }

    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = RoleSpatie::create($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseRole($role, ['message' => 'Successfully created.']);
    }

    public function show(RoleSpatie $role)
    {
        return $this->responseRole($role);
    }

    public function edit(RoleSpatie $role)
    {
        return $this->responseRole($role);
    }

    public function update(RoleRequest $request, RoleSpatie $role)
    {
        DB::beginTransaction();
        try {
            $role->update($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseRole($role, ['message' => 'Successfully updated.']);
    }

    public function destroy(RoleSpatie $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responseRole($role, ['message' => 'Successfully deleted.']);
    }

    protected function responseRole($role, $attributes = null)
    {
        $paginate = $role->search(request()->input('search'))->paginate(request()->input('size'));

        $attributes = array_merge((array) $attributes, [
            'current_page' => $paginate->currentPage(),
            'last_page' => $paginate->lastPage(),
            'per_page' => $paginate->perPage(),
            'total' => $paginate->total(),
        ]);

        return (new RoleResource($role->load(['permissions'])))->additional(['meta' => $attributes]);
    }
}
