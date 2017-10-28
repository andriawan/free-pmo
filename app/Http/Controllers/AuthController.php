<?php

namespace App\Http\Controllers;

use App\Entities\Users\User;
use App\Http\Requests\Accounts\RegisterRequest;
use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;

class AuthController extends Controller
{
    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth      = $auth;
        $this->passwords = $passwords;

        $this->middleware('guest');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $registerData = $request->only('name', 'email', 'password');

        $user = User::create($registerData);
        $user->assignRole('admin');
        $user->assignRole('worker');
        Auth::login($user);

        flash()->success(trans('auth.welcome', ['name' => $user->name]));
        return redirect()->route('home');
    }
}
