<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('join_requests', function (Blueprint $table) {
            $table->string('applicant_name', 150)->nullable()->after('id');
            $table->string('applicant_phone', 30)->nullable()->after('applicant_name');
            $table->string('applicant_email', 150)->nullable()->after('applicant_phone');
            $table->string('company_name', 200)->nullable()->after('applicant_email');

            $table->index('applicant_phone');
            $table->index('applicant_email');
        });
    }

    public function down(): void
    {
        Schema::table('join_requests', function (Blueprint $table) {
            $table->dropIndex(['applicant_phone']);
            $table->dropIndex(['applicant_email']);
            $table->dropColumn(['applicant_name', 'applicant_phone', 'applicant_email', 'company_name']);
        });
    }
};
