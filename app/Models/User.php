<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;


/**
 * @method static create(array $array)
 * @method static whereIn(string $string, $employeeUserIds)
 * @method static find(string $id)
 * @method static findOrFail(string $id)
 * @method static where(string $string, mixed $referral)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Generate a random password
    public static function generatePassword(): string
    {
        return Str::random(12); // Generate a 12-character random string
    }

    // Mutator to hash the password
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_users', 'user_id', 'business_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(UserReferral::class, 'referral_user_id');
    }

    public function referredBy(): HasMany
    {
        return $this->hasMany(UserReferral::class, 'user_id');
    }
}
