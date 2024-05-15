<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserNotification;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('login.index', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {

            $request->flashOnly('email');

            $credentials = $request->validate([
                'email' => 'required | email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(strtolower(auth()->user()->role->roleName).'/dashboard');
            }
            return back()->with('email', 'Masukkan Password anda dengan Benar');
    }

    public function logout(Request $request)
    {
        User::where('id', auth()->user()->id)->update(['isPresent' => '0']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
