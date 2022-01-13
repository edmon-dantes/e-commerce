<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;

// use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Http\JsonResponse;

class BaseFormRequest extends FormRequest
{
    protected $is_multiple = false;
    protected $format_request = 'data';

    protected function setFormatRequest($format_request)
    {
        $this->format_request = $format_request;
    }

    public function setMultiple()
    {
        $this->is_multiple = true;
        $this->format_request = $this->format_request . '.*';
    }

    protected function getFormatRequest($input)
    {
        $string = trim(join('.', array_filter([$this->format_request, $input], function ($v) {
            return !is_null($v) && $v !== '';
        })));

        $post = strpos($string, ".");
        if ($post === 0) {
            $string = substr($string, 1, strlen($string));
        }

        return $string;
    }

    private function __toFormatValidate($rules = []): array
    {
        $rules_format = [];
        foreach ($rules as $key => $rule) {
            $rules_format[$this->getFormatRequest($key)] = $rule;
        }

        return $rules_format;
    }

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
        return $this->__toFormatValidate($rules);
    }

    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        return [
            //
        ];
    }

    public function createRequest(string $request_class): BaseFormRequest
    {
        $request = $request_class::createFrom($this);
        $app = app();
        $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        return $request;
    }

    /*
protected function failedValidation(Validator $validator)
{
$errors = $validator->errors(); // (new ValidationException($validator))->errors();
$message = (method_exists($this, 'message')) ? $this->container->call([$this, 'message']) : 'The given data was invalid.';

throw new HttpResponseException(response()->json([
'errors' => $errors,
'meta' => ['message' => $message]
], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
}
 */
}
