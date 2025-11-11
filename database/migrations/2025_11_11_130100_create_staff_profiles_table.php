<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();

            // البيانات الأساسية (مصدر الفالديشن من DB)
            $table->string('full_name');                          // NOT NULL
            $table->date('birth_date')->nullable();               // اختياري
            $table->string('employee_number')->unique();          // UNIQUE + NOT NULL
            $table->string('national_id')->nullable()->unique();  // UNIQUE عند وجوده
            $table->string('job_title')->nullable();
            $table->string('location')->nullable();
            $table->string('department')->nullable();
            $table->string('directorate')->nullable();
            $table->string('section')->nullable();
            $table->enum('marital_status', ['single','married','widowed','divorced'])->nullable();
            $table->unsignedTinyInteger('family_members_count')->default(1);

            // العائلة
            $table->enum('has_family_incidents', ['no','yes'])->default('no');
            $table->text('family_notes')->nullable();

            // السكن والوضع الاجتماعي
            $table->string('original_address')->nullable();
            $table->enum('house_status', ['intact','partial','demolished'])->nullable();
            $table->enum('status', ['resident','displaced'])->nullable();
            $table->string('current_address')->nullable();
            $table->enum('housing_type', ['house','apartment','tent','other'])->nullable();

            // التواصل
            $table->string('mobile');            // NOT NULL
            $table->string('mobile_alt')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
            $table->string('gmail')->nullable();

            // الجاهزية
            $table->enum('readiness', ['ready','not_ready'])->nullable();
            $table->text('readiness_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('staff_profiles');
    }
};
