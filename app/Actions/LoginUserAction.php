<?php

namespace App\Actions;

use App\DTO\LoginData;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    /**
     * @throws ValidationException
     */
    public function __invoke(LoginData $data): ?Authenticatable
    {
        if (!Auth::attempt(['email' => $data->email, 'password' => $data->password], $data->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        session()->regenerate();

        return $user;
    }
}
