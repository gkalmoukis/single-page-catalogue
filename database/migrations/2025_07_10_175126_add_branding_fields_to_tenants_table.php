<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Logo field
            $table->string('logo_path')->nullable()->after('data');
            
            // Business details
            $table->text('business_description')->nullable()->after('logo_path');
            $table->string('phone')->nullable()->after('business_description');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            
            // Timetable - stored as JSON for flexibility
            $table->json('timetable')->nullable()->after('address');
            
            // Social media links - stored as JSON
            $table->json('social_links')->nullable()->after('timetable');
            
            // Brand colors
            $table->string('primary_color')->nullable()->default('#3B82F6')->after('social_links');
            $table->string('secondary_color')->nullable()->default('#6B7280')->after('primary_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'business_description',
                'phone',
                'email',
                'address',
                'timetable',
                'social_links',
                'primary_color',
                'secondary_color'
            ]);
        });
    }
};
