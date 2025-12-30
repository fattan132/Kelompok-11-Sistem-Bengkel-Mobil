<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_booking_id',
        'service_id',
        'price',
        'points_earned',
    ];

    public function booking()
    {
        return $this->belongsTo(ServiceBooking::class, 'service_booking_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
