<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();

            // بيانات أساسية
            $table->string('full_name'); // الاسم رباعي - مطلوب

            // الهوية و الرقم الوظيفي (يجب ألا يتكررا)
            $table->unsignedSmallInteger('employee_number')->unique(); // <=1999 وفريد
            $table->unsignedBigInteger('national_id')->unique();       // 9 أرقام وفريد

            $table->date('birth_date')->nullable();
            $table->string('job_title')->nullable();

            // المقر (قائمة محددة من 1 إلى 8)
            $table->enum('location', ['1','2','3','4','6','7','8']);

            // الأقسام الإدارية
            $table->string('department')->nullable();
            $table->string('directorate')->nullable();
            $table->string('section')->nullable();

            // الحالة الاجتماعية
            $table->enum('marital_status', ['single','married','widowed','divorced'])->nullable();
            $table->unsignedTinyInteger('family_members_count')->default(1);

            // أفراد العائلة
            $table->enum('has_family_incidents', ['no','yes'])->default('no');
            $table->text('family_notes')->nullable();

            // بيانات السكن
            $table->string('original_address')->nullable();
            $table->enum('house_status', ['intact','partial','demolished'])->nullable();
            $table->enum('status', ['resident','displaced'])->nullable();
            $table->string('current_address')->nullable();
            $table->enum('housing_type', ['house','apartment','tent','other'])->nullable();

            // التواصل
            $table->string('mobile', 10); // مطلوب ولا يزيد عن 10
            $table->string('mobile_alt', 10)->nullable();
            $table->string('whatsapp', 10)->nullable();
            $table->string('telegram', 50)->nullable();
            $table->string('gmail', 150)->nullable();

            // الجاهزية
            $table->enum('readiness', ['ready','not_ready'])->nullable();
            $table->text('readiness_notes')->nullable();

            // كلمة المرور وخصائص التعديل (بدون after)
            $table->string('password_hash')->nullable();
            $table->unsignedTinyInteger('edits_allowed')->default(1);
            $table->unsignedTinyInteger('edits_remaining')->default(1);
            $table->timestamp('last_edited_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('staff_profiles');
    }
};
