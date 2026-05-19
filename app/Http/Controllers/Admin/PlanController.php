<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanPurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = PlanPurchase::with('professional')->latest()->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        PlanPurchase::findOrFail($id)->update($request->only(['status', 'listing_limit', 'expiry']));

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        PlanPurchase::findOrFail($id)->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
