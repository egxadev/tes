<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /**
         * VIEW
         */

        //login
        Fortify::loginView(function () {
            return view('pages.auth.login');
        });

        // //register
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });

        // //forgot
        // Fortify::requestPasswordResetLinkView(function () {
        //     return view('auth.forgot-password');
        // });

        // //reset
        // Fortify::resetPasswordView(function ($request) {
        //     return view('auth.reset-password', ['request' => $request]);
        // });

        // //confirm password
        // Fortify::confirmPasswordView(function () {
        //     return view('auth.confirm-password');
        // });

        // //two factor authentication
        // Fortify::twoFactorChallengeView(function () {
        //     return view('auth.two-factor-challenge');
        // });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });
    }
}
