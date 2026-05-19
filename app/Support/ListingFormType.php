<?php

namespace App\Support;

class ListingFormType
{
    /**
     * Resolve listing type flags for create/edit forms.
     *
     * @return array{
     *     currentType: int,
     *     isBrokerListing: bool,
     *     isSaleListing: bool,
     *     isCapitalRaiseListing: bool,
     *     isGuidedListing: bool
     * }
     */
    public static function resolve(?int $listingType = null, ?int $projectType = null): array
    {
        $currentType = (int) ($listingType ?? $projectType ?? session()->get('type', 0));

        if (! in_array($currentType, [1, 2, 3, 4], true)) {
            $currentType = match (auth()->user()?->role) {
                'buyer' => 1,
                'seller' => 2,
                'capital_raiser' => 3,
                'broker' => 4,
                default => 0,
            };
        }

        $isBrokerListing = $currentType === 4;
        $isSaleListing = in_array($currentType, [2, 4], true);
        $isCapitalRaiseListing = $currentType === 3;
        $isGuidedListing = $isSaleListing || $isCapitalRaiseListing;

        return compact(
            'currentType',
            'isBrokerListing',
            'isSaleListing',
            'isCapitalRaiseListing',
            'isGuidedListing'
        );
    }
}
