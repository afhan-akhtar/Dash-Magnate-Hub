<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class ProfessionalPortalSessionService
{
    public function __construct(private SubscriptionService $subscription) {}

    /**
     * Populate legacy session keys used by the professional workspace layout and chat.
     */
    public function sync(Request $request, User $professional): void
    {
        $professional->loadMissing('documents');

        $activePlan = $this->subscription->getActivePlan($professional);

        $request->session()->put([
            'raising_id'  => $professional->id,
            'name'        => $professional->full_name ?: $professional->name,
            'email'       => $professional->email,
            'type'        => $this->legacyTypeFromRole($professional->role),
            'profile'     => optional($professional->documents->firstWhere('type', 'profile'))->file ?? null,
            'profile_url' => optional($professional->document('profile')->first())->url,
            'plan_type'   => $activePlan?->type ?? 0,
            'plan_expiry' => optional($activePlan?->expiry)->format('Y-m-d'),
        ]);
    }

    private function legacyTypeFromRole(string $role): int
    {
        $role = strtolower(trim($role));

        return match ($role) {
            'buyer' => 1,
            'seller' => 2,
            'capital_raiser' => 3,
            'broker' => 4,
            default => 0,
        };
    }
}
