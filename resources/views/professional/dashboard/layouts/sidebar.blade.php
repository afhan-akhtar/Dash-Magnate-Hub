@php
    use App\Helpers\SubscriptionHelper;

    $userType = session()->get('type');
    $canCreate = SubscriptionHelper::canCreateListing(session()->get('raising_id'));
    $isBuyer = ($userType == 1);
    $roleLabel = match ($userType) {
        1 => 'Buyer',
        2 => 'Seller',
        3 => 'Capital Raiser',
        4 => 'Broker / Franchise',
        default => 'Professional',
    };
@endphp

