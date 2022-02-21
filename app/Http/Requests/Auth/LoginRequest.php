<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $identity = $this->input('data.identity');
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $data = array_merge($this->only(['data.password'])['data'], [$field => $identity]);

        $this->merge(['data' =>  $data]);
    }

    public function rules()
    {
        return [
            'data.identity' => ['required'],
            'data.password' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'data.identity' => 'username or E-mail',
            'data.password' => 'password',
        ];
    }
}
