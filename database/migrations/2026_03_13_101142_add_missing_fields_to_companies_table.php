<?php
// database/migrations/xxxx_add_missing_fields_to_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Add missing fields for multi-step company creation
            $table->string('size')->nullable()->after('industry');
            $table->year('founded_year')->nullable()->after('size');
            $table->string('headquarters')->nullable()->after('location');
            $table->string('contact_email')->nullable()->after('headquarters');
            $table->string('phone')->nullable()->after('contact_email');
            $table->string('cover_path')->nullable()->after('logo_path');
            $table->json('culture_images')->nullable()->after('cover_path');
            $table->string('video_link')->nullable()->after('culture_images');
            $table->json('social_links')->nullable()->after('video_link');
            
            // Add index for faster queries
            $table->index('verification_status');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'size',
                'founded_year',
                'headquarters',
                'contact_email',
                'phone',
                'cover_path',
                'culture_images',
                'video_link',
                'social_links'
            ]);
        });
    }
};