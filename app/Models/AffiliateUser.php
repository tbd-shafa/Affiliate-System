<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'acc_name',
        'acc_no',
        'bank_name',
        'branch_address',
        'status',
       
    ];

    // Relationship with User
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
