<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceDifficultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $difficulties = [
            'Ganti Oli' => ['difficulty' => 'hard', 'custom' => false],
            'Servis Berkala' => ['difficulty' => 'hard', 'custom' => false],
            'Ganti Filter Udara' => ['difficulty' => 'easy', 'custom' => false],
            'Cuci Mobil Premium' => ['difficulty' => 'custom', 'custom' => true],
            'Ganti Busi' => ['difficulty' => 'easy', 'custom' => false],
            'Servis AC' => ['difficulty' => 'hard', 'custom' => false],
            'Overhaul Mesin' => ['difficulty' => 'hard', 'custom' => false],
            'Tune Up' => ['difficulty' => 'hard', 'custom' => false],
        ];

        foreach ($difficulties as $name => $config) {
            Service::where('name', $name)
                ->update([
                    'difficulty_level' => $config['difficulty'],
                    'has_custom_fee' => $config['custom'],
                ]);
        }
    }
}
