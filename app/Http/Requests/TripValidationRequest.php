<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return [
            'type' => 'required|in:umrah,hajjQ,hajjT,hajjI',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|int|max:255',
            'days_num_madinah' => 'required|int|max:255',
            'price' => 'required|numeric',
            'start_date' => 'required',
            'is_active'  => 'required|boolean',
        ];
    }
}
