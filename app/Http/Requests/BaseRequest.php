<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return auth('api')->check();
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'  => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }
        parent::failedValidation($validator);
    }
}
