<?php

namespace App\Http\Requests\Admin;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    /* allow admin category update */
    public function authorize(): bool
    {
        return true;
    }

    /* define category update rules */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
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