<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class UsersService
{
    public function index(UserRequest $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'username',
                'email',
                'fullname',
            ]);

        return $users;
    }

    public function create(): User
    {
        $user = new User();

        return $user;
    }

    public function store(UserRequest $request): User
    {
        DB::beginTransaction();
        try {

            $user = User::create($request->input('data'));
            // $user->assignRole('client');

            $collection = collect($request->input('data.roles', []));
            $plucked = $collection->pluck('id');
            $user->roles()->sync($plucked->all());

            $collection = collect($request->input('data.permissions', []));
            $plucked = $collection->pluck('id');
            $user->syncPermissions($plucked->all());

            $user->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $user;
    }

    public function update(UserRequest $request, User $user): User
    {
        DB::beginTransaction();
        try {

            $user->update($request->input('data'));

            $collection = collect($request->input('data.roles', []));
            $plucked = $collection->pluck('id');
            $user->roles()->sync($plucked->all());
            // if (!count($plucked->all())) {
            //     $user->assignRole('client');
            // }

            $collection = collect($request->input('data.permissions', []));
            $plucked = $collection->pluck('id');
            $user->syncPermissions($plucked->all());

            $user->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $user;
    }

    public function destroy(User $user): User
    {
        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $user;
    }
}
