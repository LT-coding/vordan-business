<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static whereHas(string $string, \Closure $param)
 * @method static findOrFail(string $id)
 */
class Business extends Model
{
    protected $fillable = [
        'company_name', 'verified', 'avatar',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users');
    }

    public function account(): HasOne
    {
        return $this->hasOne(BusinessAccount::class, 'business_id');
    }
}
