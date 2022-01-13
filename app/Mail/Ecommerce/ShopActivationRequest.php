<?php

namespace App\Mail\Ecommerce;

use App\Models\Ecommerce\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShopActivationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function build()
    {
        return $this->markdown('mail.ecomerce.shop-activation');
    }
}
