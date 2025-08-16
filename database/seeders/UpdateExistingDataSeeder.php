<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;

class UpdateExistingDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get the PMP certificate (first one)
        $pmpCertificate = Certificate::where('code', 'PMP')->first();
        
        if (!$pmpCertificate) {
            $this->command->error('PMP certificate not found. Please run CertificateSeeder first.');
            return;
        }

        $this->command->info('Updating existing data with certificate_id: ' . $pmpCertificate->id);

        // Update existing domains
        DB::table('domains')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        // Update existing chapters
        DB::table('chapters')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        // Update existing exams
        DB::table('exams')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        // Update existing plans
        DB::table('plans')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        // Update existing user_progress
        DB::table('user_progress')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        // Update existing achievements
        DB::table('achievements')->whereNull('certificate_id')->update([
            'certificate_id' => $pmpCertificate->id
        ]);

        $this->command->info('Successfully updated existing data with certificate_id.');
    }
} 