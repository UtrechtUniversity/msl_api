<?php

namespace App\Http\Requests;

use App\Http\Resources\V2\Errors\ValidationErrorResource;
use App\Rules\GeoRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GeoJsonDataPublicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer', 'min:0'],
            'pageSize' => ['required', 'integer', 'min:0'],
            'boundingBox' => ['required', new GeoRule],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = $validator->getException();
        throw new HttpResponseException((
            new ValidationErrorResource(
                new $exception($validator)
            )
        )->response());
    }
}
