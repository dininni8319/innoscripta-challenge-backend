<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email:rfc,dns',
            'password' => 'required|min:6|confirmed', 
        ];
    }

      public function messages()
    {
        return [
            'name.required' => "Name is required",
            'email.required' => "Email is required",
            'email.email' => "Email is not valid",
            'password.required' => "Password is required",
            'password.min' => "The password must be at least 6 characters long",
            'password.confirmed' => "The passwords are not the same",
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
