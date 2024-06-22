<?php

// In app/Models/UserReferral.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class UserReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_user_id',
        'user_id',
    ];

    public function referralUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
