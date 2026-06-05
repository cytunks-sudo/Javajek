<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
   protected $fillable = [
    'code',
    'name',
    'image',
    'type',
    'service_type',
    'value',
    'minimum_order',
    'maximum_discount',
    'quota',
    'used_count',
    'is_new_user_only',
    'is_active',
    'start_date',
    'end_date',
];

public function usages()
{
    return $this->hasMany(\App\Models\VoucherUsage::class);
}

}