<?php

namespace App\Classes\Paypal;

use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreatePayment extends Paypal
{
    public function create($order): string
    {
        $payment = $this->Payment($order);

        try {
            $payment->create($this->apiContext);
        } catch (PayPalConnectionException $e) {
            throw new UnprocessableEntityHttpException($e->getData());
        }

        return $payment->getApprovalLink();
    }

    protected function Payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        return $payer;
    }

    protected function Payee(): Payee
    {
        $payee = new Payee();
        $payee->setEmail('armanrcb@gmail.com');
        return $payee;
    }

    protected function Transaction($order): Transaction
    {
        $items = array_map(function ($item) {
            $newItem = new Item();
            $newItem->setName($item['pivot']['name'])
                ->setCurrency('USD')
                ->setQuantity($item['pivot']['quantity'])
                ->setPrice($item['pivot']['price']);
            return $newItem;
        }, $order->items->toArray());

        $item_list = new ItemList();
        $item_list->setItems($items);

        $transaction = new Transaction();
        $transaction->setAmount($this->Amount($order->grand_total))
            ->setItemList($item_list)
            ->setDescription('Enter Your transaction description')
            ->setPayee($this->Payee())
            ->setInvoiceNumber(uniqid());
        return $transaction;
    }

    protected function RedirectUrls($order): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success', $order->number))
            ->setCancelUrl(route('paypal.cancel', $order->number));
        return $redirectUrls;
    }

    protected function Payment($order): Payment
    {
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($this->Payer())
            ->setRedirectUrls($this->RedirectUrls($order))
            ->setTransactions(array($this->Transaction($order)));
        return $payment;
    }
}
