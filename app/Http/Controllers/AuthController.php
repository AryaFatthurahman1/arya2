<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ], [
            'login.required' => 'Nama atau email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Login validasi gagal', [
                'ip' => $request->ip(),
                'errors' => $validator->errors(),
            ]);
            return back()->withErrors($validator)->withInput();
        }

        $login = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $login)
            ->orWhere('name', $login)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            Log::info('Login berhasil', [
                'user_id' => $user->id,
                'name' => $user->name,
                'ip' => $request->ip(),
            ]);

            return redirect()->intended('dashboard');
        }

        Log::warning('Login gagal', [
            'login' => $login,
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'login' => 'Nama/email atau password salah.',
        ])->onlyInput('login');
    }

    public function showTwoFactorForm()
    {
        return view('auth.2fa');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->session()->put('2fa_passed', true);
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logout', ['user_id' => $userId]);

        return redirect('/');
    }
}
