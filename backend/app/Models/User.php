<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'currency',
        'current_savings',
        'monthly_savings_target',
        'email_notifications',
        'referral_code',
        'referred_by_id',
        'commission_rate',
        'referral_clicks',
        'payout_method',
        'payout_details',
    ];

    protected $appends = ['is_premium', 'referral_link'];

    protected $casts = [
        'payout_details' => 'encrypted:json',
        'email_notifications' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });
    }

    protected static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function getReferralLinkAttribute()
    {
        return config('app.frontend_url', 'http://localhost:5173') . '/register?ref=' . $this->referral_code;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class , 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class , 'referred_by_id');
    }

    public function commissions()
    {
        return $this->hasMany(ReferralCommission::class , 'referrer_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_secret' => 'encrypted',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function pathway()
    {
        return $this->hasMany(Pathway::class);
    }

    public function activePathway()
    {
        return $this->hasOne(Pathway::class)->where('status', 'active')->latestOfMany();
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function timelineSteps()
    {
        return $this->hasMany(UserTimelineStep::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function professionalProfile()
    {
        return $this->hasOne(ProfessionalProfile::class);
    }

    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function isPremium(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', now());
        })
            ->exists();
    }

    public function getIsPremiumAttribute(): bool
    {
        return $this->isPremium();
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function riskReports()
    {
        return $this->hasMany(RiskReport::class);
    }

    public function actionLogs()
    {
        return $this->hasMany(UserActionLog::class);
    }
}