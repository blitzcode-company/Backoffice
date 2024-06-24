<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $this->ensureIsNotRateLimited($request);
        $credentials = [
            'samaccountname' => $request->username,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            $this->handleFailedLogin($request);
        }

        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $this->sendLockoutResponse($request);
        }
    }

    protected function handleFailedLogin(Request $request): void
    {
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }

    protected function sendLockoutResponse(Request $request): void
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
