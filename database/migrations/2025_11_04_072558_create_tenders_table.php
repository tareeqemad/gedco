<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            // PK: يزيد تلقائيًا (id +1)
            $table->bigIncrements('id');

            // الأعمدة الثمانية كما طلبت
            $table->integer('mnews_id')->nullable();            // MNEWS_ID
            $table->string('column_name_1', 255)->nullable();   // COLUMN_NAME_1

            // HTML طويل / نصوص طويلة
            $table->longText('old_value_1')->nullable();        // OLD_VALUE_1
            $table->longText('new_value_1')->nullable();        // NEW_VALUE_1

            $table->string('the_date_1', 50)->nullable();       // THE_DATE_1 (as-is)

            $table->string('event_1', 255)->nullable();         // EVENT_1
            $table->string('the_user_1', 255)->nullable();      // THE_USER_1
            $table->integer('coulm_serial')->nullable();        // COULM_SERIAL

            // طوابع Laravel (اختياري مفيد)
            $table->timestamps();

            // فهارس مفيدة (اختياري)
            $table->index('mnews_id');
            $table->index('the_date_1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
