<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email:rfc,dns',
            'password' => 'required|min:6', 
        ];
    }

      public function messages()
    {
        return [
            'email.required' => "Email is required",
            'email.email' => "Email is not valid",
            'password.required' => "Password is required",
            'password.min' => "The password must be at least 6 characters long",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        
        $response = response()->json([
            'success' => false,
            'message' => 'Invalid data',
            'details' => $errors->messages(),
        ], 422);
    
        throw new HttpResponseException($response);
    }
}
