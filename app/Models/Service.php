<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'points_earned',
        'difficulty_level',
        'has_custom_fee',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_custom_fee' => 'boolean',
    ];

    // Helper method to get fee based on difficulty
    public function getAutoFee()
    {
        if ($this->has_custom_fee) {
            return 0;
        }

        return match($this->difficulty_level) {
            'hard' => 350000,
            'easy' => 100000,
            default => 50000,
        };
    }

    // Relasi ke ServiceBooking
    public function serviceBookings()
    {
        return $this->hasMany(ServiceBooking::class);
    }
}
