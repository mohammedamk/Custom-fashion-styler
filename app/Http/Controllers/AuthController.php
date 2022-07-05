<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login_form()
    {

        $this->setTitle( 'Login' );

        return view( 'auth.login' );
    }

    public function login( LoginRequest $request )
    {

        $success = auth()->attempt( $request->only( [
            'email',
            'password'
        ] ) );

        if( ! $success )
        {
            throw ValidationException::withMessages( [

                'email'         => 'Invalid credentials.'

            ] );
        }

        return [
            'success'       => true,
            'redirect'      => \Redirect::intended( route( 'dashboard' ) )->getTargetUrl()
        ];
    }
    public function logout()
    {

        auth()->logout();

        return \redirect( route( 'portals.signin' ) );
    }

    public function forgotPassword( $locale = null )
    {

        return view('auth.forgot-password');
    }

    public function forgotPasswordProcess( Request $request )
    {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(

            $request->only('email')
        );

        if( $status !== Password::RESET_LINK_SENT )
        {
            throw ValidationException::withMessages( [

                'email'         => __($status)
            ] );
        }

        return [

            'success'       => true,
            'message'       => __($status)
        ];
    }

    public function resetPassword( $token = null )
    {

        return view('auth.reset-password')
            ->with( 'token', $token )
            ->with( 'email', \request( 'email' ) );
    }

    public function resetPasswordProcess( Request $request )
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {

                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                \auth()->login( $user, true );
            }
        );

        if( $status !== Password::PASSWORD_RESET )
        {
            throw ValidationException::withMessages( [

                'email'     => [__($status)]

            ] );
        }

        return [

            'success'       => true,
            'redirect'      => route( 'dashboard' )
        ];
    }

}
