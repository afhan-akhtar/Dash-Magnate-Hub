<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof User && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user instanceof User && $user->isProfessional()) {
                return redirect()->route('professional.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // First authenticate by credentials only; enforce admin role after.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            Auth::logout();

            return back()->withErrors(['email' => 'Only admin accounts can log in here.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
