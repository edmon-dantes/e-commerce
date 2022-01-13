<?php

namespace App\Observers;

use App\Mail\Ecommerce\ShopActivated;
use App\Mail\Ecommerce\ShopActivationRequest;
use App\Models\Ecommerce\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ShopObserver
{
    public function created(Shop $shop)
    {
        $super_admin = User::where(['username' => 'super-admin'])->get();

        Mail::to($super_admin->email)->send(new ShopActivationRequest($shop));
    }

    public function updated(Shop $shop)
    {
        // check if active column is changed from inactive  to active
        if ($shop->getOriginal('is_active') == false && $shop->is_active == true) {

            //send mail to customer
            Mail::to($shop->user->email)->send(new ShopActivated($shop));

            //change role from customer  to seller
            $shop->user->assignRole('seller');
        }

        // send mail to customer
        Mail::to($shop->user->email)->send(new ShopActivated($shop));
    }

    public function deleted(Shop $shop)
    {
        //
    }

    public function restored(Shop $shop)
    {
        //
    }

    public function forceDeleted(Shop $shop)
    {
        //
    }
}
