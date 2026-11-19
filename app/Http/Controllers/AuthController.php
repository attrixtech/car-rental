<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Record last login
            /** @var User $user */
            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function quickLogin(Request $request, string $role)
    {
        $emailMap = [
            'admin' => 'admin@agracarrental.in',
            'fleet' => 'fleet@agracarrental.in',
            'staff' => 'staff@agracarrental.in',
            'driver' => 'driver@agracarrental.in',
            'customer' => 'customer@agracarrental.in',
        ];

        $email = $emailMap[$role] ?? 'admin@agracarrental.in';
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', "Signed in as {$user->name} ({$user->role})");
        }

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

