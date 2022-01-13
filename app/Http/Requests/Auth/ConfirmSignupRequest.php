<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;

class ConfirmSignupRequest extends BaseFormRequest
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
        return [
            'email' => 'required|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/|max:255|exists:password_resets,email',
            'token' => 'required'
        ];
    }
}
