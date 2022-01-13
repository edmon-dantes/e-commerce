<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRequest extends BaseFormRequest
{
    public function view()
    {
        return [
            //
        ];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255'],
            'data.lastname' => ['nullable', 'string', 'max:255'],
            'data.mothers_lastname' => ['nullable', 'string', 'max:255'],
            'data.username' => ['required', 'string', 'min:5', 'max:255', 'unique:users,username'],
            'data.email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'data.phone_number' => ['required', 'string', 'max:50'],
            'data.password' => ['required', 'alpha_num', 'between:6,12', 'confirmed'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:users,id'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.lastname' => ['nullable', 'string', 'max:255'],
            'data.mothers_lastname' => ['nullable', 'string', 'max:255'],
            'data.username' => ['required', 'string', 'min:5', 'max:255', Rule::unique('users', 'username')->ignore($this->user)],
            'data.email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($this->user)],
            'data.phone_number' => ['required', 'string', 'max:50'],
            'data.password' => ['required_if:change_password,true', 'alpha_num', 'between:6,12', 'confirmed'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.lastname' => 'lastname',
            'data.mothers_lastname' => 'mothers_lastname',
            'data.username' => 'username',
            'data.email' => 'email',
            'data.phone_number' => 'phone_number',
            'data.password' => 'password',
            'data.status' => 'status',
            'data.photo' => 'photo',
        ];
    }
}
