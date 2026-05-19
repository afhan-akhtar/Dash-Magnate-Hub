<?php

namespace App\Helpers;

use DB;

class SubscriptionHelper
{
    /**
     * Plan type constants
     */
    const PLAN_FREE = 5;             // Free Package
    const PLAN_ESSENTIALS = 1;       // Essentials Package
    const PLAN_PREMIUM = 2;          // Premium Package
    const PLAN_BROKER_PRO = 3;       // Broker Pro Package
    const PLAN_CAPITAL_RAISE = 4;    // Capital Raise Package

    /**
     * Get active plan for a user
     */
    public static function getActivePlan($professionalId)
    {
        return DB::table('plan_purchases')
            ->where([
                ['professional_id', $professionalId],
                ['status', 0]
            ])
            ->where(function ($query) {
                $query->whereNull('expiry')
                    ->orWhere('expiry', '>=', date('Y-m-d'));
            })
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Get listing limit for a plan type
     */
    public static function getListingLimit($plan_type)
    {
        switch ($plan_type) {
            case self::PLAN_FREE:
                return 1; // Free plan: 1 listing
            case self::PLAN_ESSENTIALS:
                return 1; // Essentials: 1 listing
            case self::PLAN_CAPITAL_RAISE:
                return 1; // Capital Raise: 1 listing
            case self::PLAN_BROKER_PRO:
                return null; // Unlimited listings
            case self::PLAN_PREMIUM:
                return 1; // Premium: 1 listing
            default:
                return 0; // No plan or invalid plan
        }
    }

    /**
     * Check if plan has unlimited listings
     */
    public static function hasUnlimitedListings($plan_type)
    {
        return in_array($plan_type, [self::PLAN_BROKER_PRO], true);
    }

    /**
     * Count user's active listings (excluding deleted)
     * Counts all statuses (draft, published) but excludes deleted
     */
    public static function countUserListings($professionalId)
    {
        return DB::table('projects')
            ->where('professional_id', $professionalId)
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check if user can create more listings
     */
    public static function canCreateListing($professionalId)
    {
        $plan = self::getActivePlan($professionalId);
        
        if (!$plan) {
            return false; // No active plan
        }

        // Unlimited plans
        if (self::hasUnlimitedListings($plan->type)) {
            return true;
        }

        // Check listing limit
        $limit = self::getListingLimit($plan->type);
        if ($limit === null) {
            return true; // Shouldn't happen, but safety check
        }

        $currentCount = self::countUserListings($professionalId);
        return $currentCount < $limit;
    }

    /**
     * Get remaining listing slots
     */
    public static function getRemainingListings($professionalId)
    {
        $plan = self::getActivePlan($professionalId);
        
        if (!$plan) {
            return 0;
        }

        if (self::hasUnlimitedListings($plan->type)) {
            return 'unlimited';
        }

        $limit = self::getListingLimit($plan->type);
        $currentCount = self::countUserListings($professionalId);
        
        return max(0, $limit - $currentCount);
    }

    /**
     * Get plan information for display
     */
    public static function getPlanInfo($plan_type)
    {
        $plans = [
            self::PLAN_FREE => [
                'name' => 'Free Package',
                'listing_limit' => 1,
                'duration' => '3 months'
            ],
            self::PLAN_ESSENTIALS => [
                'name' => 'Essentials Package',
                'listing_limit' => 1,
                'duration' => '6 months'
            ],
            self::PLAN_CAPITAL_RAISE => [
                'name' => 'Capital Raise Package',
                'listing_limit' => 1,
                'duration' => '12 months'
            ],
            self::PLAN_BROKER_PRO => [
                'name' => 'Broker Pro Package',
                'listing_limit' => 'unlimited',
                'duration' => '1 year'
            ],
            self::PLAN_PREMIUM => [
                'name' => 'Premium Package',
                'listing_limit' => 1,
                'duration' => 'Until sold'
            ]
        ];

        return $plans[$plan_type] ?? null;
    }
}

