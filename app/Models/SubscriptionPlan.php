<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    //
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'package_id', 'id');
    }
}
