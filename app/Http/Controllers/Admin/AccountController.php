<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = User::admins()->latest()->get();

        return view('admin.accounts.index', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'role' => 'admin',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code' => md5(uniqid()),
            'verified' => 1,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Admin account created.');
    }

    public function destroy(int $id): RedirectResponse
    {
        User::admins()->findOrFail($id)->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Admin account deleted.');
    }
}
