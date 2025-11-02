<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('advertisements')) {
            Schema::create('advertisements', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';

                // المفتاح الأساسي (بدون AUTO_INCREMENT أثناء الترحيل)
                $table->bigIncrements('ID_ADVER')->primary();

                $table->text('TITLE')->nullable();
                $table->text('TITLE_E')->nullable();
                $table->dateTime('DATE_NEWS')->nullable();
                $table->longText('BODY')->nullable();
                $table->longText('BODY_E')->nullable();
                $table->text('PDF')->nullable();
                $table->string('INSERT_USER', 200)->nullable();
                $table->string('UPDATE_USER', 200)->nullable();
                $table->dateTime('INSERT_DATE')->nullable();
                $table->dateTime('UPDATE_DATE')->nullable();
                $table->text('WORD')->nullable();
                $table->dateTime('DATE_NEWS1')->nullable();

                // فهارس
                $table->index('DATE_NEWS', 'advertisements_date_news_index');
                $table->index('INSERT_DATE', 'advertisements_insert_date_index');
                $table->index('UPDATE_DATE', 'advertisements_update_date_index');
                $table->index('INSERT_USER', 'advertisements_insert_user_index');
                $table->index('UPDATE_USER', 'advertisements_update_user_index');
            });

            return; // خلّصنا في حالة الإنشاء
        }

        // لو الجدول موجود: أضِف فقط الفهارس الناقصة
        $indexes = DB::table('information_schema.statistics')
            ->select('index_name')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'advertisements')
            ->pluck('index_name')
            ->all();

        $ensureIndex = function (string $col, string $name) use ($indexes) {
            if (! in_array($name, $indexes, true)) {
                Schema::table('advertisements', function (Blueprint $table) use ($col, $name) {
                    $table->index($col, $name);
                });
            }
        };

        $ensureIndex('DATE_NEWS',  'advertisements_date_news_index');
        $ensureIndex('INSERT_DATE','advertisements_insert_date_index');
        $ensureIndex('UPDATE_DATE','advertisements_update_date_index');
        $ensureIndex('INSERT_USER','advertisements_insert_user_index');
        $ensureIndex('UPDATE_USER','advertisements_update_user_index');
    }

    public function down(): void
    {
        // لو بدك rollback كامل:
        // Schema::dropIfExists('advertisements');

        // أو بس إسقاط الفهارس (اختياري):
        $dropIfExists = function (string $name) {
            $exists = DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'advertisements')
                ->where('index_name', $name)
                ->exists();

            if ($exists) {
                Schema::table('advertisements', function (Blueprint $table) use ($name) {
                    $table->dropIndex($name);
                });
            }
        };

        $dropIfExists('advertisements_date_news_index');
        $dropIfExists('advertisements_insert_date_index');
        $dropIfExists('advertisements_update_date_index');
        $dropIfExists('advertisements_insert_user_index');
        $dropIfExists('advertisements_update_user_index');
    }
};
