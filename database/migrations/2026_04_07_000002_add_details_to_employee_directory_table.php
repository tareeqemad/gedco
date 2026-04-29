<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_directory', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('full_name');
            $table->string('mobile', 15)->nullable()->after('job_title');
            $table->string('email')->nullable()->after('mobile');
            $table->string('spouse_name')->nullable()->after('email');
            $table->string('spouse_national_id', 9)->nullable()->after('spouse_name');
            $table->unsignedTinyInteger('family_members_count')->nullable()->after('spouse_national_id');
            $table->string('governorate', 20)->nullable()->after('family_members_count');
            $table->string('employment_status', 20)->nullable()->after('governorate');
        });
    }

    public function down(): void
    {
        Schema::table('employee_directory', function (Blueprint $table) {
            $table->dropColumn([
                'job_title', 'mobile', 'email',
                'spouse_name', 'spouse_national_id',
                'family_members_count', 'governorate', 'employment_status',
            ]);
        });
    }
};
