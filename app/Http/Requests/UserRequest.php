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
            'data.address' => ['required', 'string', 'max:255'],
            'data.password' => ['required', 'alpha_num', 'between:6,12', 'confirmed'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.picture.name' => ['string', 'max:255'],
            'data.picture.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],


            'data.roles' => ['array'],
            // 'data.roles.*.size' => ['required', 'string', 'max:255', 'distinct'],
            // 'data.attributes.*.sku' => ['required', 'string', 'max:255', 'distinct', 'unique:products_attributes,sku'],

        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\User,id'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.lastname' => ['nullable', 'string', 'max:255'],
            'data.mothers_lastname' => ['nullable', 'string', 'max:255'],
            'data.username' => ['required', 'string', 'min:5', 'max:255', Rule::unique('users', 'username')->ignore($this->user)],
            'data.email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($this->user)],
            'data.phone_number' => ['required', 'string', 'max:50'],
            'data.address' => ['required', 'string', 'max:255'],
            'data.password' => ['required_if:change_password,true', 'alpha_num', 'between:6,12', 'confirmed'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.picture.name' => ['string', 'max:255'],
            'data.picture.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],

            'data.roles' => ['array'],
            // 'data.roles.*.size' => ['required', 'string', 'max:255', 'distinct'],
            // 'data.roles.*.sku' => ['required', 'string', 'max:255', 'distinct', Rule::unique('products_attributes', 'sku')->ignore($this->product->id, 'product_id')],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\User,id'],
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
            'data.address' => 'address',
            'data.password' => 'password',
            'data.status' => 'status',
            'data.picture' => 'picture',
            'data.roles' => 'roles',
        ];
    }
}
