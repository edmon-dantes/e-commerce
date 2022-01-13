<?php

namespace App\Http\Requests\Ecommerce;

use App\Http\Requests\BaseFormRequest;

class PaypalRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules['paymentId'] = 'required';
        $rules['PayerID'] = 'required';
        $rules['token'] = 'required';

        return $rules;
    }
}
