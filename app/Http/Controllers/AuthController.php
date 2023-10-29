<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\UserNotification;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::check()){
            if (auth()->user()->level == 'Apoteker') {
                $request->session()->regenerate();
                return redirect()->intended('/apoteker/dashboard');
            } elseif (auth()->user()->level == 'Dokter') {
                $request->session()->regenerate();
                return redirect()->intended('/dokter/resep/create');
            } elseif (auth()->user()->level == 'Kasir') {
                $request->session()->regenerate();
                return redirect()->intended('/kasir/pasien/list');
            }
        }else{
            return view('login.index', [
                'title' => 'Login',
            ]);
        }
    }

    public function authenticate(Request $request)
    {

            $request->flashOnly('email');

            $credentials = $request->validate([
                'email' => 'required | email',
                'password' => 'required',
            ]);

            // $user = User::where(['email' => $request->email, 'password' => bcrypt($request->password)])->first();
            // dd($credentials);
            if (Auth::attempt($credentials)) {
                User::where('id', auth()->user()->id)->update(['isPresent' => '1']);
                // event(new UserNotification(auth()->user()->level, auth()->user()->nama, auth()->user()->nama.' is logged on',''));

                if (auth()->user()->level == 'Apoteker') {
                    $request->session()->regenerate();
                    return redirect()->intended('/apoteker/dashboard');
                } elseif (auth()->user()->level == 'Dokter') {
                    $request->session()->regenerate();
                    return redirect()->intended('/dokter/resep/create');
                } elseif (auth()->user()->level == 'Kasir') {
                    $request->session()->regenerate();
                    return redirect()->intended('/kasir/pasien/list');
                }
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
