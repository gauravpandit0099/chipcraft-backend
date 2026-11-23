<?php

namespace App\Http\Requests\Admin;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubcategoryRequest extends FormRequest
{
    /* allow admin subcategory update */
    public function authorize(): bool
    {
        return true;
    }

    /* define subcategory update rules */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100|unique:subcategories,name,' . $id,
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