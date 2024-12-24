<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateReferral extends Model
{
    use HasFactory;

    protected $fillable = ['referrer_id', 'user_id'];

    public function affiliateUser()
    {
        return $this->belongsTo(AffiliateUser::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}