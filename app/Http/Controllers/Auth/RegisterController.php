<?php

namespace App\Http\Controllers\Auth;

use App\Actions\RegisterNewUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request, RegisterNewUserAction $action)
    {
        $user = $action($request->toData());

        Auth::login($user);

        return redirect()->intended('dashboard')->with('flash', ['type' => 'success', 'message' => 'Registration successful! Welcome aboard.']);
    }
}
