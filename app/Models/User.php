<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke ServiceBooking
    public function serviceBookings()
    {
        return $this->hasMany(ServiceBooking::class);
    }

    // Relasi ke UserVoucher
    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    // Check apakah user adalah customer
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Check apakah user adalah kasir
    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    // Check apakah user adalah manager
    public function isManager()
    {
        return $this->role === 'manager';
    }
}
