<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::regularUsers()
            ->where('is_deleted', 0)
            ->with('thumbnail')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(int $id): View
    {
        $user = User::regularUsers()
            ->with(['documents', 'wishlists.project'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        User::regularUsers()->findOrFail($id)->update($request->only(['status']));

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        User::regularUsers()->findOrFail($id)->update(['is_deleted' => 1]);

        return redirect()->route('admin.users.index')->with('success', 'User removed.');
    }
}
