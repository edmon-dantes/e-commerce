<?php
namespace App\Classes\Paypal;

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Transaction;
use phpDocumentor\Reflection\Types\Boolean;

class ExecutePayment extends Paypal
{
    public function execute($order)
    {
        $payment = $this->GetPayment();

        $execution = $this->CreateExecution();

        $execution->addTransaction($this->Transaction($order));

        $result = $payment->execute($execution, $this->apiContext);

        if (Str::upper($result->getState()) === 'APPROVED') {
            // dd(json_decode($result->toJSON(128), true));
            return $result;
        }

        return null;
    }

    protected function GetPayment(): Payment
    {
        $paymentId = request()->input('paymentId');
        return Payment::get($paymentId, $this->apiContext);
    }

    protected function CreateExecution(): PaymentExecution
    {
        $execution = new PaymentExecution();
        $execution->setPayerId(request()->input('PayerID'));
        return $execution;
    }

    protected function Transaction($order): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->Amount($order->grand_total));
        return $transaction;
    }
}
