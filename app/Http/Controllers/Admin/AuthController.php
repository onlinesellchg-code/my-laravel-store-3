<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (session('admin_authenticated')) return redirect()->route('admin.dashboard');
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $validUser = hash_equals((string) config('admin.username', 'admin'), (string) $data['username']);
        $validPass = hash_equals((string) config('admin.password', ''), (string) $data['password']);

        if (! $validUser || ! $validPass) {
            return back()->withErrors(['login' => 'نام کاربری یا رمز عبور اشتباه است.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
