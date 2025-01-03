<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payout extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'affiliate_user_id',
        'amount',
        'payment_by',
        'remarks',
    ];

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id', 'id');
    }
}
