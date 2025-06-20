<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class OfficeStoreRequest extends FormRequest
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

    #[ArrayShape(['name' => "string[]", 'address' => "string[]", 'license_number' => "array"])]
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'license_number' => ['required', 'numeric', Rule::unique('offices', 'license_number')],
        ];
    }
}
