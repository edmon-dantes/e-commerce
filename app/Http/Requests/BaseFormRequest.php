<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = match ($this->method()) {
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update(),
            'DELETE' => $this->destroy(),
            default => $this->view()
        };
        return $rules;
    }
    public function view()
    {
        return [
            //
        ];
    }

    public function store()
    {
        return [
            //
        ];
    }

    public function update()
    {
        return [
            //
        ];
    }

    public function destroy()
    {
        return [
            //
        ];
    }
}
