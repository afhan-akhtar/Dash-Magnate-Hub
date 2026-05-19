<?php

namespace App\Models;

use App\Traits\HasDocuments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasDocuments;

    protected $table = 'projects';

    protected $fillable = [
        'user_id',
        'professional_id',
        'category_id',
        'location_id',
        'region_id',
        'ref_id',
        'name',
        'price',
        'trading',
        'earning_type',
        'stock_level',
        'summary',
        'location_information',
        'description',
        'url',
        'code',
        'status',
        'active',
        'premium',
        'block',
        'sold',
        'under_offer',
        'urgent_sale',
        'franchise',
        'multiple_locations',
        'rating',
        'views',
        'type',
        'skills',
        'potential',
        'hours',
        'staff',
        'lease',
        'business_established',
        'training',
        'awards',
        'reason_for_sale',
        'seeking_investment',
        'reported_sales',
        'run_rate_sales',
        'ebitda_margin',
        'industry',
        'assets_or_collateral',
        'interested_to_connect_with_advisors',
        'business_overview',
        'products_and_services_overview',
        'assets_overview',
        'facilities_overview',
        'capitalization_overview',
    ];

    protected $casts = [
        'price'           => 'integer',
        'status'          => 'integer',
        'active'          => 'integer',
        'deleted_at'      => 'datetime',
        'premium'         => 'integer',
        'block'           => 'integer',
        'sold'            => 'integer',
        'under_offer'     => 'integer',
        'urgent_sale'     => 'integer',
        'franchise'       => 'integer',
        'multiple_locations' => 'integer',
        'rating'          => 'integer',
        'views'           => 'integer',
        'type'            => 'integer',
        'user_id'         => 'integer',
        'professional_id' => 'integer',
        'category_id'     => 'integer',
        'location_id'     => 'integer',
        'region_id'       => 'integer',
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Whether the listing is soft-deleted (`status` = 1 and/or `deleted_at` set).
     */
    public function isDeleted(): bool
    {
        return (int) $this->status === 1 || $this->deleted_at !== null;
    }

    /**
     * Shown on the public website / website API (active, not deleted, not blocked, not sold).
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeVisibleOnWebsite($query)
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->where('active', 1)
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->where('block', 0)
            ->where('sold', 0)
            ->whereHas('professional.planPurchases', function ($planQuery) use ($today) {
                $planQuery->where('status', 0)
                    ->where(function ($expiryQuery) use ($today) {
                        $expiryQuery->whereNull('expiry')
                            ->orWhere('expiry', '>=', $today);
                    });
            });
    }

    /**
     * Not soft-deleted (either flag may be used; both are kept in sync on delete).
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('status', 0)->whereNull('deleted_at');
    }
}
