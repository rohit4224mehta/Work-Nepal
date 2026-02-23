<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Update users table for WorkNepal platform.
 *
 * Adds:
 * - Mobile authentication
 * - Profile fields
 * - Account status
 * - Security tracking
 * - Soft deletes
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Mobile Authentication
            $table->string('mobile', 20)->nullable()->unique()->after('email');
            $table->timestamp('mobile_verified_at')->nullable()->after('email_verified_at');

            // Make email nullable (for mobile-only login support)
            $table->string('email')->nullable()->change();

            // Profile Fields
            $table->string('profile_photo_path')->nullable()->after('password');

            $table->enum('gender', [
                'male',
                'female',
                'other',
                'prefer_not_to_say'
            ])->nullable()->after('profile_photo_path');

            $table->date('date_of_birth')->nullable()->after('gender');

            // Account Control
            $table->enum('account_status', [
                'active',
                'suspended'
            ])->default('active')->after('date_of_birth');

            // Security Tracking
            $table->timestamp('last_login_at')->nullable()->after('account_status');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');

            // Soft Deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'mobile',
                'mobile_verified_at',
                'profile_photo_path',
                'gender',
                'date_of_birth',
                'account_status',
                'last_login_at',
                'last_login_ip',
                'deleted_at'
            ]);
        });
    }
};