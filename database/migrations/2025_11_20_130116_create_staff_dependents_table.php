<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('staff_dependents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_profile_id')
                ->constrained('staff_profiles')
                ->cascadeOnDelete()
                ->index();

            $table->string('name');
            $table->string('relation', 20);
            $table->date('birth_date')->nullable();
            $table->boolean('is_student')->default(false);

            $table->timestamps();
            $table->softDeletes();


            $table->unique(['staff_profile_id', 'name', 'birth_date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('staff_dependents');
    }
};
