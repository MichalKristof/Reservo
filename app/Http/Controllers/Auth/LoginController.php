<?php

namespace App\Http\Controllers\Auth;

use App\Actions\LoginUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request, LoginUserAction $action): RedirectResponse
    {
        $user = $action($request->toData());

        Auth::login($user, $request->toData()->remember);

        return redirect()->intended('dashboard')->with('flash', ['type' => 'success', 'message' => 'You have been logged in successfully.']);
    }
}
