<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFeeTemplate extends Model
{
    protected $fillable = [
        'name',
        'fee',
        'description',
        'is_active',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
