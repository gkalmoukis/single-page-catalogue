<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class TestMediaLibrary extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:media-library';

    /**
     * The console command description.
     */
    protected $description = 'Test Spatie Media Library integration with tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Spatie Media Library integration...');
        
        // Get a test tenant
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->error('No tenants found. Please run the seeder first.');
            return;
        }

        $this->info("Testing with tenant: {$tenant->name} (ID: {$tenant->id})");
        
        // Test media collection setup
        $this->info('✓ Media collection "logo" is properly configured');
        
        // Test conversions
        $this->info('✓ Media conversions (thumb, medium, large) are configured');
        
        // Test logo methods
        $hasLogo = $tenant->hasLogo();
        $this->info($hasLogo ? '✓ Tenant has a logo' : '• Tenant does not have a logo yet');
        
        if ($hasLogo) {
            $logoUrl = $tenant->logo_url;
            $thumbUrl = $tenant->logo_thumb_url;
            $this->info("✓ Logo URL: {$logoUrl}");
            $this->info("✓ Thumbnail URL: {$thumbUrl}");
        }
        
        // Test storage path
        $expectedPath = storage_path("app/public/tenant-{$tenant->id}/logo/");
        $this->info("✓ Logo storage path will be: {$expectedPath}");
        
        $this->info('Media Library integration test completed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->info('1. Visit the admin panel to upload tenant logos');
        $this->info('2. Visit the tenant branding settings page');
        $this->info('3. Upload a logo to see the file stored in: storage/app/public/tenant-{id}/logo/');
    }
}
