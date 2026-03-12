<?php
// database/migrations/[timestamp]_create_notifications_table.php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type'); // job_application, job_alert, message, etc.
            $table->string('title');
            $table->text('message')->nullable();
            $table->json('data')->nullable(); // Additional data
            $table->timestamp('read_at')->nullable();
            $table->morphs('notifiable'); // Creates notifiable_id and notifiable_type
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};