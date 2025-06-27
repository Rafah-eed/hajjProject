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
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['office_id' => "string", 'type' => "string", 'regiment_name' => "string", 'days_num_makkah' => "string", 'days_num_madinah' => "string", 'price' => "string", 'start_date' => "string", 'is_active' => "string"])]
    public function rules(): array
    {
        return [
            'type' => 'required|in:umrah,hajj',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|integer|max:255',
            'days_num_madinah' => 'required|integer|max:255',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_active' => 'required|boolean',
            'numOfReservations' => 'integer|min:0',
            'trip_code' => 'integer',

        ];
    }
}