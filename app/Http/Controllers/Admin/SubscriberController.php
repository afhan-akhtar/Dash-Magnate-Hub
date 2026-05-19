<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function index(): View
    {
        $subscribers = Subscriber::latest()->get();

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy(int $id): RedirectResponse
    {
        Subscriber::findOrFail($id)->delete();

        return redirect()->route('admin.subscribers.index')->with('success', 'Subscriber removed.');
    }
}
