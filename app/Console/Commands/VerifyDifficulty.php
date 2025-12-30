<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;

class VerifyDifficulty extends Command
{
    protected $signature = 'verify:difficulty';
    protected $description = 'Verify service difficulty levels';

    public function handle()
    {
        $this->info('=== Service Difficulty & Fee Verification ===');
        $this->newLine();

        $services = Service::all();

        foreach ($services as $service) {
            $this->info("Service: {$service->name}");
            $this->line("  Difficulty: {$service->difficulty_level}");
            $this->line("  Has Custom Fee: " . ($service->has_custom_fee ? 'Yes' : 'No'));
            $this->line("  Auto Fee: Rp " . number_format($service->getAutoFee(), 0, ',', '.'));
            $this->newLine();
        }

        $this->info('Verification completed successfully!');
    }
}
