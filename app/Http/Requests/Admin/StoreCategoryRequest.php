<?php

namespace App\Http\Requests\Admin;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
{
    /* allow admin category creation */
    public function authorize(): bool
    {
        return true;
    }

    /* define category creation rules */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name',
            'status' => 'required|boolean',
        ];
    }

    /* return json validation errors */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ResponseHelper::error('Validation failed', 422, $validator->errors())
        );
    }
}