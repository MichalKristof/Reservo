<?php

namespace App\Http\Requests\Auth;

use App\DTO\LoginData;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function toData(): LoginData
    {
        return new LoginData(
            email: $this->input('email'),
            password: $this->input('password'),
            remember: $this->boolean('remember', false),
        );
    }
}
