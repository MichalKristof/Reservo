<?php

namespace App\Actions;

use App\DTO\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterNewUserAction
{
    public function __invoke(RegisterData $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
            ]);
        });
    }
}
