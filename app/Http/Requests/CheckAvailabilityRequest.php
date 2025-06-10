<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckAvailabilityRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reserved_at' => ['sometimes', 'required', 'date', 'after_or_equal:today'],
            'time' => ['sometimes', 'required', 'nullable'],
            'duration' => ['sometimes', 'integer', 'nullable'],
            'number_of_people' => ['sometimes', 'integer', 'min:1', 'max:6', 'nullable'],
        ];
    }
}
