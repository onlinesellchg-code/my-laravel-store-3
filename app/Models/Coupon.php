<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code','type','value','usage_limit','used_count','expires_at','is_active'];
    protected $casts = ['value'=>'integer','usage_limit'=>'integer','used_count'=>'integer','expires_at'=>'date','is_active'=>'boolean'];

    public function isUsable(): bool
    {
        return $this->is_active && (! $this->expires_at || $this->expires_at->isFuture())
            && (! $this->usage_limit || $this->used_count < $this->usage_limit);
    }
}
