<?php

namespace App\Http\Controllers\Ecommerce;

use App\Classes\Paypal\CreatePayment;
use App\Classes\Paypal\ExecutePayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\Ecommerce\PaypalRequest;
use App\Mail\OrderPaid;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Str;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payment;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaypalController extends Controller
{
    public function create($order)
    {
        $payment = new CreatePayment;

        return (new OrderController)->responseOrder($order, array(
            'message' => 'Successfully created. Link paypal send',
            'paypal_link' => $payment->create($order)
        ));
    }

    public function execute(BaseFormRequest $baseFormRequest, Order $order)
    {
        $request = $baseFormRequest->convertRequest(PaypalRequest::class);

        $payment = new ExecutePayment;

        if (!$result = $payment->execute($order)) {
            return $this->cancel($baseFormRequest, $order);
        }

        $order->status = 'completed';
        $order->is_paid = true;
        $order->save();

        return (new OrderController)->responseOrder($order, array(
            'message' => 'Order Paided'
        ));
    }

    public function cancel(BaseFormRequest $baseFormRequest, Order $order)
    {
        throw new UnprocessableEntityHttpException('Payment Unsuccessfull! Something went wrong!');
    }
}
