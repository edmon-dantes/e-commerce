<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RoleRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.permissions' => ['array'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Role,id'],
            'data.name' => ['required', 'string', 'max:255',  Rule::unique('roles', 'name')->ignore($this->role)],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.permissions' => ['array'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Role,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.status' => 'status',
            'data.permissions' => 'permissions',
        ];
    }
}
