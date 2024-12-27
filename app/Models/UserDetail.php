<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details'; // Optional if the table name matches convention

    protected $fillable = [
        'user_id', 'address', 'acc_name', 'acc_no', 'bank_name', 'branch_address', 
        'phone_number', 'percentage_value', 'status', 'affiliate_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
