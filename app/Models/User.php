<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    // public function affiliateUser()
    // {
    //     return $this->hasOne(AffiliateUser::class);
    // }

    

    public function referrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'referrer_id');
    }


    public function affiliateReferrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'referrer_id');
    }
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'user_id', 'id');
    }

    //after new setup
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Get the user's role (assuming only one role per user).
     */
    public function getRoleAttribute()
    {
        return $this->roles->first()->name ?? 'user'; // Default to 'user' if no role is found
    }
    public function affiliateUser()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }
   
}
