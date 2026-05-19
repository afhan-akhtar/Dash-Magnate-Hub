<?php

namespace App\Services;

use App\Helpers\SubscriptionHelper;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Models\Project;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function packageCatalogQuery()
    {
        return Plan::query()->catalog()->where('status', 0);
    }

    public function getPackageByType(int $type): ?Plan
    {
        return $this->packageCatalogQuery()
            ->where('type', $type)
            ->first();
    }

    public function getAvailablePackageTypeIds(): array
    {
        $typeIds = $this->packageCatalogQuery()
            ->pluck('type')
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        if ($typeIds !== []) {
            return $typeIds;
        }

        return [
            SubscriptionHelper::PLAN_FREE,
            SubscriptionHelper::PLAN_ESSENTIALS,
            SubscriptionHelper::PLAN_PREMIUM,
            SubscriptionHelper::PLAN_BROKER_PRO,
            SubscriptionHelper::PLAN_CAPITAL_RAISE,
        ];
    }

    public function createPlan(User $professional, array $data): PlanPurchase
    {
        $package = $this->getPackageByType((int) $data['type']);
        $durationDays = $data['duration_days'] ?? $package?->duration_days;
        $expiry = $durationDays ? Carbon::now()->addDays((int) $durationDays) : null;

        return PlanPurchase::create([
            'professional_id' => $professional->id,
            'plan_id'         => $package?->id,
            'type'            => (int) $data['type'],
            'name'            => $data['name'] ?? $package?->name,
            'slug'            => $data['slug'] ?? $package?->slug,
            'expiry'          => $expiry,
            'listing_limit'   => $data['listing_limit'] ?? $package?->listing_limit,
            'price'           => $data['price'] ?? $package?->price ?? 0,
            'duration_days'   => $durationDays,
            'timeframe_label' => $data['timeframe_label'] ?? $package?->timeframe_label,
            'premium_marking_limit' => $data['premium_marking_limit'] ?? $package?->premium_marking_limit ?? 0,
            'premium_marking_used' => 0,
            'inclusions'      => $data['inclusions'] ?? $package?->inclusions,
            'billing_first_name' => $data['billing_first_name'] ?? null,
            'billing_last_name' => $data['billing_last_name'] ?? null,
            'billing_business_name' => $data['billing_business_name'] ?? null,
            'billing_abn' => $data['billing_abn'] ?? null,
            'billing_email' => $data['billing_email'] ?? null,
            'billing_phone' => $data['billing_phone'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'status'          => 0,
            'code'            => md5(uniqid()),
        ]);
    }

    public function getActivePlan(User $professional): ?PlanPurchase
    {
        return PlanPurchase::where('professional_id', $professional->id)
            ->where('status', 0)
            ->where(function ($query) {
                $query->whereNull('expiry')
                    ->orWhere('expiry', '>=', Carbon::today());
            })
            ->latest()
            ->first();
    }

    public function hasListingQuota(User $professional): bool
    {
        $activePlans = PlanPurchase::query()
            ->where('professional_id', $professional->id)
            ->where('status', 0)
            ->where(function ($query) {
                $query->whereNull('expiry')
                    ->orWhere('expiry', '>=', Carbon::today());
            })
            ->get();

        if ($activePlans->isEmpty()) {
            return false;
        }

        if ($activePlans->contains(fn (PlanPurchase $plan) => $plan->listing_limit === null)) {
            return true;
        }

        $totalListingLimit = (int) $activePlans
            ->sum(fn (PlanPurchase $plan) => max(0, (int) $plan->listing_limit));

        $used = $professional->projects()->notDeleted()->count();
        return $used < $totalListingLimit;
    }

    public function remainingPremiumMarks(User $professional): int
    {
        $plan = $this->getActivePlan($professional);
        if (! $plan) {
            return 0;
        }

        return max(0, (int) $plan->premium_marking_limit - (int) $plan->premium_marking_used);
    }

    public function applyPremiumMark(User $professional, Project $project): bool
    {
        $plan = $this->getActivePlan($professional);

        if (! $plan || $project->professional_id !== $professional->id) {
            return false;
        }

        if ($project->isDeleted() || (int) $project->active !== 1 || (int) $project->premium === 1) {
            return false;
        }

        if ($this->remainingPremiumMarks($professional) < 1) {
            return false;
        }

        DB::transaction(function () use ($plan, $project): void {
            $project->update(['premium' => 1]);
            $plan->increment('premium_marking_used');
        });

        return true;
    }

    public function subscribe(string $email): Subscriber
    {
        return Subscriber::firstOrCreate(
            ['email' => $email],
            ['code' => md5(uniqid())]
        );
    }
}
