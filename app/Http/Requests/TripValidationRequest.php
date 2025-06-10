<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class TripValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['type' => "string", 'regiment_name' => "string", 'days_num_makkah' => "string", 'days_num_madinah' => "string", 'price' => "string", 'start_date' => "string", 'is_active' => "string"])] public function rules()
    {
        return [
            'office_id' => 'required|exists:offices,id',
            'type' => 'required|in:umrah,hajj',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|int|max:255',
            'days_num_madinah' => 'required|int|max:255',
            'price' => 'required|numeric',
            'start_date' => 'required',
            'is_active'  => 'required|boolean',
        ];
    }
}
