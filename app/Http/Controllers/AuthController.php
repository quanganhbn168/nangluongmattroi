<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm(Request $request)
    {
        $isAdmin = $request->is('admin') || $request->is('admin/*');
        return view($isAdmin ? 'auth.admin.login' : 'auth.client.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $isAdmin = $request->is('admin/*');
        $guard = $isAdmin ? 'admin' : 'web';
        if (Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($isAdmin ? route('admin.dashboard') : route('home'));
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    // Hiển thị form đăng ký (dành cho user)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký (chỉ dành cho user, không áp dụng cho admin)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $isAdmin = $request->is('admin/*');
        $guard = $isAdmin ? 'admin' : 'web';

        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($isAdmin ? route('admin.login') : route('home'));
    }
}
