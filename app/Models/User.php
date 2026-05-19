<?php

namespace App\Models;

use App\Traits\HasDocuments;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasDocuments;

    protected $table = 'users';

    /**
     * Roles that map to the old raising.type:
     *   buyer          = type 1
     *   seller         = type 2
     *   capital_raiser = type 3
     *   broker         = type 4  (Broker/Franchise)
     */
    const PROFESSIONAL_ROLES = ['buyer', 'seller', 'capital_raiser', 'broker'];

    protected $fillable = [
        'role',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        // Professional-specific (null for admin/user)
        'nationality',
        'gender',
        'company_name',
        'earning',
        'net_worth',
        'tin',
        // Sub-user link (points to a professional-role user)
        'professional_id',
        // Auth state
        'otp',
        'verified',
        'status',
        'is_deleted',
        'code',
        // Buyer-profile fields (user role)
        'seeking',
        'reported_sales',
        'run_rate_sales',
        'ebitda_margin',
        'industry',
        'location_id',
        'assets_or_collateral',
        'interested',
        'title',
        'description',
        'business_overview',
        'product_and_service_overview',
        'assets_overview',
        'facilities_overview',
        'capitalization_overview',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'otp'             => 'integer',
        'verified'        => 'integer',
        'status'          => 'integer',
        'is_deleted'      => 'integer',
        'interested'      => 'integer',
        'location_id'     => 'integer',
        'professional_id' => 'integer',
        'gender'          => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Role helpers
    // -------------------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Professionals: buyer, seller, capital_raiser, broker — these roles use the professional portal.
     */
    public function isProfessional(): bool
    {
        $role = strtolower(trim((string) $this->role));

        return in_array($role, array_map('strtolower', self::PROFESSIONAL_ROLES), true);
    }

    public function isBuyer(): bool        { return $this->role === 'buyer'; }
    public function isSeller(): bool       { return $this->role === 'seller'; }
    public function isCapitalRaiser(): bool { return $this->role === 'capital_raiser'; }
    public function isBroker(): bool       { return $this->role === 'broker'; }

    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return $this->name;
    }

    // -------------------------------------------------------------------------
    // Query scopes
    // -------------------------------------------------------------------------

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    public function scopeProfessionals(Builder $query): Builder
    {
        return $query->whereIn('role', self::PROFESSIONAL_ROLES);
    }

    public function scopeRegularUsers(Builder $query): Builder
    {
        return $query->where('role', 'user');
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Projects listed by this professional (seller/broker etc.) */
    public function projects()
    {
        return $this->hasMany(Project::class, 'professional_id');
    }

    /** Purchased/subscribed plans for this professional (legacy alias). */
    public function plans()
    {
        return $this->hasMany(PlanPurchase::class, 'professional_id');
    }

    /** Purchased/subscribed plans for this professional */
    public function planPurchases()
    {
        return $this->hasMany(PlanPurchase::class, 'professional_id');
    }

    /** Payment transactions for this professional */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'professional_id');
    }

    public function professionalQuestionAnswers()
    {
        return $this->hasMany(ProfessionalQuestionAnswer::class);
    }

    /** Chats where this user is the buyer side */
    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    /** Chats where this user is the professional (seller) side */
    public function professionalChats()
    {
        return $this->hasMany(Chat::class, 'professional_id');
    }

    /** Wishlist entries (user role) */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /** Sub-users managed by this professional */
    public function subUsers()
    {
        return $this->hasMany(User::class, 'professional_id');
    }

    /** The professional who manages this sub-user */
    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }
}

