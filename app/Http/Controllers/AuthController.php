<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('login.index', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request){
        $request->flashOnly('email');


        $credentials = $request->validate([
            'email' => 'required | email',
            'password' => 'required'
        ]);

        // $user = User::where(['email' => $request->email, 'password' => bcrypt($request->password)])->first();
        // dd($credentials);
        if(Auth::attempt($credentials)){
            if(auth()->user()->level == 'Apoteker'){
                $request->session()->regenerate();
                return redirect()->intended('/apoteker/dashboard');
            }
            elseif(auth()->user()->level == 'Dokter'){
                $request->session()->regenerate();
                return redirect()->intended('/dokter/dashboard');
                User::where(auth()->user()->id)->update('isPresent', 1);
            }
            elseif(auth()->user()->level == 'Kasir'){
                $request->session()->regenerate();
                return redirect()->intended('/kasir/dashboard');
            }
        }
        return back()->with('email', 'Masukkan Password anda dengan Benar');

    }

    public function logout(Request $request){
        User::where(['level' => 0, 'id' => auth()->user()->id])->update(['isPresent' => '0']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
