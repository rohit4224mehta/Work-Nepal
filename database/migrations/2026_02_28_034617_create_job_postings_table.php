<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');             // active, pending, closed
            $table->string('verification_status')->default('pending'); // verified, pending, rejected
            $table->string('location')->nullable();
            $table->string('job_type')->nullable();                   // full-time, part-time, etc.
            $table->string('salary_range')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};