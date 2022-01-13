<?php

namespace App\Policies\Ecommerce;

use App\Models\User;
use App\Models\Ecommerce\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasRole('seller');
    }

    public function view(User $user, Shop $shop)
    {
        return $user->id === $shop->user_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Shop $shop)
    {
        return $user->id === $shop->user_id;
    }

    public function delete(User $user, Shop $shop)
    {
        return $user->id === $shop->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop  $shop
     * @return mixed
     */
    public function restore(User $user, Shop $shop)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shop  $shop
     * @return mixed
     */
    public function forceDelete(User $user, Shop $shop)
    {
        //
    }
}
