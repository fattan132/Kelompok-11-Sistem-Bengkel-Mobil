<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\ServiceFeeTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Manager (skip if exists)
        if (!User::where('email', 'manager@servis.com')->exists()) {
            User::create([
                'name' => 'Manager Utama',
                'email' => 'manager@servis.com',
                'phone' => '081234567890',
                'address' => 'Jl. Manager No. 1',
                'password' => bcrypt('password123'),
                'role' => 'manager',
                'points' => 0,
            ]);
        }

        // Create Kasir/Admin (skip if exists)
        if (!User::where('email', 'kasir@servis.com')->exists()) {
            User::create([
                'name' => 'Kasir Servis',
                'email' => 'kasir@servis.com',
                'phone' => '081234567891',
                'address' => 'Jl. Kasir No. 2',
                'password' => bcrypt('password123'),
                'role' => 'kasir',
                'points' => 0,
            ]);
        }

        // Create Sample Customers (skip if exists)
        if (!User::where('email', 'customer1@example.com')->exists()) {
            User::create([
                'name' => 'John Doe',
                'email' => 'customer1@example.com',
                'phone' => '081111111111',
                'address' => 'Jl. Customer No. 1',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'points' => 50,
            ]);

            User::factory(5)->create([
                'role' => 'customer',
            ]);
        }

        // Create Services (skip if exists)
        if (Service::count() === 0) {
            Service::create([
                'name' => 'Ganti Oli',
                'description' => 'Penggantian oli mesin dengan oli berkualitas premium',
                'price' => 150000,
                'points_earned' => 15,
            ]);

            Service::create([
                'name' => 'Servis Berkala',
                'description' => 'Servis berkala 10.000 km termasuk cek seluruh sistem',
                'price' => 250000,
                'points_earned' => 25,
            ]);

            Service::create([
                'name' => 'Ganti Filter Udara',
                'description' => 'Penggantian filter udara mesin',
                'price' => 100000,
                'points_earned' => 10,
            ]);

            Service::create([
                'name' => 'Cuci Mobil Premium',
                'description' => 'Pencucian mobil lengkap dengan wax coating',
                'price' => 200000,
                'points_earned' => 20,
            ]);

            Service::create([
                'name' => 'Ganti Busi',
                'description' => 'Penggantian busi dengan busi original',
                'price' => 120000,
                'points_earned' => 12,
            ]);

            Service::create([
                'name' => 'Servis AC',
                'description' => 'Pembersihan dan pengisian ulang freon AC',
                'price' => 300000,
                'points_earned' => 30,
            ]);

            Service::create([
                'name' => 'Overhaul Mesin',
                'description' => 'Overhaul mesin lengkap dengan penggantian suku cadang',
                'price' => 2000000,
                'points_earned' => 100,
            ]);

            Service::create([
                'name' => 'Tune Up',
                'description' => 'Penyetelan mesin untuk performa maksimal',
                'price' => 180000,
                'points_earned' => 18,
            ]);
        }

        // Create Service Fee Templates (skip if exists)
        if (ServiceFeeTemplate::count() === 0) {
            ServiceFeeTemplate::create([
                'name' => 'Tidak Ada',
                'fee' => 0,
                'description' => 'Tanpa biaya jasa',
                'is_active' => true,
            ]);

            ServiceFeeTemplate::create([
                'name' => 'Standar',
                'fee' => 50000,
                'description' => 'Biaya jasa standar',
                'is_active' => true,
            ]);

            ServiceFeeTemplate::create([
                'name' => 'Premium',
                'fee' => 100000,
                'description' => 'Biaya jasa premium dengan layanan ekstra',
                'is_active' => true,
            ]);

            ServiceFeeTemplate::create([
                'name' => 'VIP',
                'fee' => 150000,
                'description' => 'Biaya jasa VIP dengan prioritas tinggi',
                'is_active' => true,
            ]);
        }
    }
}
