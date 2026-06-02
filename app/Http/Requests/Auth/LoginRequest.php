<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (User::where('username', $login)->exists()) {
            $field = 'username';
        } elseif (User::where('phone', $login)->exists()) {
            $field = 'phone';
        } else {
            $field = 'email';
        }

        if (! Auth::attempt([
            $field => $login,
            'password' => $this->password,
        ], $this->boolean('remember'))) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Login gagal. Periksa email, username, nomor HP, atau password.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        $this->session()->regenerate();
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->input('login')).'|'.$this->ip()
        );
    }
}