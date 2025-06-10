<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
     #[ArrayShape(['first_name' => "string", 'last_name' => "string", 'email' => "string", 'password' => "string", 'c_password' => "string", 'phone_number' => "string"])] public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'c_password' => 'required|same:password',
            'phone_number' => 'required',
        ];
    }
}
