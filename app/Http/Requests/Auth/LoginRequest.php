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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        // custom login - support email or phone number
        $loginField = $this->email;
        $isPhone = preg_match('/^[0-9+\-\(\)\s]+$/', $loginField);

        // Search by email or mobile_no based on input format
        if ($isPhone) {
            $users = User::where('mobile_no', $loginField)->get();
        } else {
            $users = User::where('email', $loginField)->get();
        }

        $id = 0;
        if (count($users) > 0) {
            foreach ($users as $key => $user) {
                if (password_verify($this->password, $user->password)) {
                    if ($user->is_enable_login != 1 || $user->is_disable != 1 && $user->type != "super admin") {
                        throw ValidationException::withMessages([
                            'email' => __("Your account is disabled from company"),
                        ]);
                    }
                    $id = $user->id;
                    break;
                }
            }
        } else {
            throw ValidationException::withMessages([
                'email' => $isPhone ? __("This phone number doesn't match") : __("This email doesn't match"),
            ]);
        }

        // Attempt login with appropriate field
        $credentials = $isPhone
            ? ['mobile_no' => $loginField, 'password' => $this->password, 'id' => $id]
            : ['email' => $loginField, 'password' => $this->password, 'id' => $id];

        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
