<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ServiceBookingItem;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'vehicle_model',
        'vehicle_number',
        'booking_date',
        'booking_time',
        'notes',
        'mechanic_notes',
        'status',
        'payment_status',
        'payment_method',
        'total_price',
        'service_fee',
        'subtotal',
        'tax_amount',
        'points_given',
        'receipt_number',
        'completed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'completed_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Item layanan yang dipesan dalam satu booking
    public function items(): HasMany
    {
        return $this->hasMany(ServiceBookingItem::class, 'service_booking_id');
    }

    // Layanan yang dipesan melalui pivot items
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_booking_items')
            ->withPivot(['price', 'points_earned'])
            ->withTimestamps();
    }
}
