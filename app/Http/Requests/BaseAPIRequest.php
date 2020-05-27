<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseAPIRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errorResponse = [];

        $errorResponse['errors'] = $validator->errors();

        throw new HttpResponseException(response()->json(
            $errorResponse,
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
