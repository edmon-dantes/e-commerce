<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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

    public function view(User $user, Product $product)
    {
        return $user->id === $product->shop->user_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('seller');
    }

    public function update(User $user, Product $product)
    {
        return $user->id === $product->shop->user_id;
    }

    public function delete(User $user, Product $product)
    {
        return $user->id === $product->shop->user_id;
    }

    public function restore(User $user, Product $product)
    {
        //
    }

    public function forceDelete(User $user, Product $product)
    {
        //
    }
}
