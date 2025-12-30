<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\Service;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();
        $gantiOli = $services->where('name', 'Ganti Oli')->first();

        $vouchers = [
            [
                'code' => 'DISCOUNT10',
                'name' => 'Diskon 10%',
                'description' => 'Dapatkan diskon 10% untuk semua layanan servis',
                'type' => 'discount_percentage',
                'value' => 10,
                'free_service_id' => null,
                'points_required' => 100,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'DISCOUNT15',
                'name' => 'Diskon 15%',
                'description' => 'Dapatkan diskon 15% untuk semua layanan servis',
                'type' => 'discount_percentage',
                'value' => 15,
                'free_service_id' => null,
                'points_required' => 200,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'DISCOUNT20',
                'name' => 'Diskon 20%',
                'description' => 'Dapatkan diskon 20% untuk semua layanan servis',
                'type' => 'discount_percentage',
                'value' => 20,
                'free_service_id' => null,
                'points_required' => 350,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'CASHBACK50',
                'name' => 'Cashback Rp 50.000',
                'description' => 'Hemat Rp 50.000 untuk layanan servis Anda',
                'type' => 'discount_fixed',
                'value' => 50000,
                'free_service_id' => null,
                'points_required' => 250,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'CASHBACK100',
                'name' => 'Cashback Rp 100.000',
                'description' => 'Hemat Rp 100.000 untuk layanan servis Anda',
                'type' => 'discount_fixed',
                'value' => 100000,
                'free_service_id' => null,
                'points_required' => 500,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
        ];

        if ($gantiOli) {
            $vouchers[] = [
                'code' => 'FREEOIL',
                'name' => 'Ganti Oli Gratis',
                'description' => 'Dapatkan layanan ganti oli gratis',
                'type' => 'free_service',
                'value' => null,
                'free_service_id' => $gantiOli->id,
                'points_required' => 150,
                'max_redemptions' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ];
        }

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
