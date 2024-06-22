<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessAccount extends Model
{
    protected $fillable = [
        'business_id', 'tax_code', 'register_code', 'registered_address', 'activity_address', 'verified',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
