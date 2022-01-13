<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;

class ResetPasswordRequest extends BaseFormRequest
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
        $rules['email'] = 'required|string|email|max:255|exists:users';
        $rules['password'] = 'required|string|min:6|confirmed';
        $rules['token'] = 'required';

        return $rules;
    }
}
