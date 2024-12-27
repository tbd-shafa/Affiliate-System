<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'affiliate_user_id',
        'subscriptions_id',
        'package_amount',
        'earn_amount',
        'package_id',
        'percentage',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id', 'id');
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'package_id', 'id');
    }
}
