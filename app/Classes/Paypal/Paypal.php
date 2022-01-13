<?php
namespace App\Classes\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal
{
    protected $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );

        $this->apiContext->setConfig(config('paypal.settings'));
    }

    protected function Details(): Details
    {
        $details = new Details();
        $details->setShipping(1.20)
            ->setTax(1.30)
            ->setSubtotal(17.50);
        return $details;
    }

    protected function Amount($total): Amount
    {
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($this->Details());
        return $amount;
    }
}
