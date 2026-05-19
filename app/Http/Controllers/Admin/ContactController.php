<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'contact');

        $query = Contact::query()->where('is_deleted', 0);

        if ($tab === 'newsletter') {
            $query->where('type', 'News Letter Form');
        } elseif ($tab === 'all') {
            // no type filter
        } else {
            $tab = 'contact';
            $query->whereIn('type', ['Contact Form', 'contact']);
        }

        $contacts = $query->latest()->get();

        return view('admin.contacts.index', compact('contacts', 'tab'));
    }

    public function show(int $id): View
    {
        $contact = Contact::query()
            ->where('is_deleted', 0)
            ->findOrFail($id);

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        Contact::findOrFail($id)->update(['is_deleted' => 1]);

        $tab = $request->get('tab', 'contact');
        if (! in_array($tab, ['contact', 'newsletter', 'all'], true)) {
            $tab = 'contact';
        }

        return redirect()->route('admin.contacts.index', ['tab' => $tab])->with('success', 'Contact removed.');
    }
}
