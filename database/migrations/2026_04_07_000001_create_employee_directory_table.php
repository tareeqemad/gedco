<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_directory', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('employee_number')->unique();
            $table->string('national_id', 9)->index();
            $table->string('full_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_directory');
    }
};
