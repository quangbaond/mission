<?php

namespace App\Services\Traits;

use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait AuthenticatesUsers
{
    /**
     * Attempt to log the user into the application.
     *
     * @param array $credentials
     * @param bool $remember
     * @param bool $isApi
     *
     * @return Authenticatable|boolean
     */
    protected function attemptLogin(array $credentials = [], bool $remember = false, bool $isApi = false)
    {
        $request = request();

        if (!$validated = $this->guard()->attempt($credentials, (($isApi) ? $isApi : $remember))) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->limiter()->hit($this->throttleKey($request), $this->decayMinutes());

            return false;
        }

        $this->clearLoginAttempts($request);

        if ($isApi) {
            return $validated;
        }

        // Send the response after the user was authenticated.
        $request->session()->regenerate();

        return $this->guard()->user();
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only('email', 'password');
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email|' . $request->ip()));
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes(): int
    {
        return 1;
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param Request $request
     *
     * @return void
     */
    protected function clearLoginAttempts(Request $request): void
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Get the rate limiter instance.
     *
     * @return RateLimiter
     */
    protected function limiter(): RateLimiter
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }
}
