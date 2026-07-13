<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        // Cek apakah akun aktif
        if (!Auth::user()->is_aktif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the Admin for more information.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectByRole()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->intended('/admin/dashboard'),
            'pic'   => redirect()->intended('/pic/dashboard'),
            default => redirect()->intended('/progress-achievement'),
        };
    }
}