<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;

class ForgotPasswordRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
    }

    public function rules()
    {
        $rules = [];
        $rules['email'] = 'required|string|email|max:255|exists:users|confirmed';

        return $rules;
    }
}
