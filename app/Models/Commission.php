<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = ['affiliate_user_id', 'amount', 'package_name', 'percentage'];

    public function affiliateUser()
    {
        return $this->belongsTo(AffiliateUser::class);
    }
}
