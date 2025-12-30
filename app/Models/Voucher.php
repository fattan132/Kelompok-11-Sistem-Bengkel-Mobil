<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'free_service_id',
        'points_required',
        'max_redemptions',
        'times_redeemed',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function freeService(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'free_service_id');
    }

    public function userVouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->startOfDay();
        
        if ($this->valid_from > $now || $this->valid_until < $now) {
            return false;
        }

        if ($this->max_redemptions && $this->times_redeemed >= $this->max_redemptions) {
            return false;
        }

        return true;
    }

    public function canBeRedeemedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($user->points < $this->points_required) {
            return false;
        }

        return true;
    }
}
