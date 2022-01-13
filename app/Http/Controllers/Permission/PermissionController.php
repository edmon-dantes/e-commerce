<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\Permission\PermissionRequest;
use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;
use App\Models\Permission\PermissionSpatie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = PermissionSpatie::with('roles')
            ->search($request->input('_search'))
            ->sort($request->input('_sort'))
            ->paginate($request->input('_size'));

        return (new PermissionCollection($permissions))->additional([
            'meta' => [
                'collections' => (object)[]
            ]
        ]);
    }

    public function create()
    {
        $permission = new PermissionSpatie;
        return $this->responsePermission($permission);
    }

    public function store(BaseFormRequest $baseFormRequest)
    {
        $request = $baseFormRequest->convertRequest(PermissionRequest::class);

        DB::beginTransaction();
        try {
            $permission = PermissionSpatie::create($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responsePermission($permission, ['message' => 'Successfully created.']);
    }

    public function show(PermissionSpatie $permission)
    {
        return $this->responsePermission($permission);
    }

    public function edit(PermissionSpatie $permission)
    {
        return $this->responsePermission($permission);
    }

    public function update(BaseFormRequest $baseFormRequest, PermissionSpatie $permission)
    {
        $request = $baseFormRequest->convertRequest(PermissionRequest::class);

        DB::beginTransaction();
        try {
            $permission->update($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responsePermission($permission, ['message' => 'Successfully updated.']);
    }

    public function destroy(PermissionSpatie $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $this->responsePermission($permission, ['message' => 'Successfully deleted.']);
    }

    protected function responsePermission($permission, $attributes = null)
    {
        $paginate = $permission->search(request()->input('search'))->paginate(request()->input('size'));

        $attributes = array_merge((array) $attributes, [
            'current_page' => $paginate->currentPage(),
            'last_page' => $paginate->lastPage(),
            'per_page' => $paginate->perPage(),
            'total' => $paginate->total(),
        ]);

        return (new PermissionResource($permission->load(['roles'])))->additional(['meta' => $attributes]);
    }
}
