<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_benefits_to_job_postings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBenefitsToJobPostingsTable extends Migration
{
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->text('benefits')->nullable()->after('description');
            $table->text('requirements')->nullable()->after('benefits');
            $table->text('skills')->nullable()->after('requirements');
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['benefits', 'requirements', 'skills']);
        });
    }
}