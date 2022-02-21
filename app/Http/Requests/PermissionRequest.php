<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PermissionRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Permission,id'],
            'data.name' => ['required', 'string', 'max:255',  Rule::unique('permissions', 'name')->ignore($this->permission)],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Permission,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.status' => 'status',
        ];
    }
}
