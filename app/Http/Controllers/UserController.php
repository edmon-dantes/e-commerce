<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class UserController extends Controller
{
    const MODEL_WITH = ['photo'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(User::class)->allowedFilters([AllowedFilter::exact('id'), 'fullname'])->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => []];

        return (new UserCollection($models))->additional($additional);
    }

    public function create()
    {
        $user = new User();

        $additional = ['collections' => []];

        return (new UserResource($user))->additional($additional);
    }

    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {

            $user = User::create($request->input('data'));
            $user->assignRole('client');

            $user->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(User $user)
    {
        $additional = ['collections' => []];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(User $user)
    {
        return $this->show($user);
    }

    public function update(UserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {

            $user->update($request->input('data'));

            $user->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new UserResource($user->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {

            $user->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new UserResource($user))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
