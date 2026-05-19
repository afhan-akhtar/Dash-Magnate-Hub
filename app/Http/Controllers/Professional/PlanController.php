<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\PlanPurchase;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function __construct(private SubscriptionService $subscription) {}

    public function index(): View
    {
        $professional = Auth::user();

        $plans = PlanPurchase::where('professional_id', $professional->id)
            ->latest()
            ->get();

        return view('professional.dashboard.plans.index', [
            'plans' => $plans,
            'activePlan' => $this->subscription->getActivePlan($professional),
        ]);
    }
}
