<?php
namespace App\Http\Requests\Auth;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class LoginRequest extends FormRequest
{
    /* allow public login request */
    public function authorize(): bool
    {
        return true;
    }

    /* define login validation rules */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    /* return JSON validation errors */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ResponseHelper::error('Validation failed', 422, $validator->errors())
        );
    }
}