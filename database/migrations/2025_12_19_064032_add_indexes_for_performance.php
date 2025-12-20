<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add composite indexes for frequently queried columns
        
        // For sliders: language + is_active + sort_order (common query pattern)
        if (Schema::hasColumn('sliders', 'language')) {
            Schema::table('sliders', function (Blueprint $table) {
                // Check if index doesn't exist
                $indexes = DB::table('information_schema.statistics')
                    ->where('table_schema', DB::getDatabaseName())
                    ->where('table_name', 'sliders')
                    ->where('index_name', 'sliders_language_active_sort_index')
                    ->exists();
                    
                if (!$indexes) {
                    $table->index(['language', 'is_active', 'sort_order'], 'sliders_language_active_sort_index');
                }
            });
        }

        // For news: language + status + published_at (common query pattern)
        if (Schema::hasColumn('news', 'language')) {
            Schema::table('news', function (Blueprint $table) {
                $indexes = DB::table('information_schema.statistics')
                    ->where('table_schema', DB::getDatabaseName())
                    ->where('table_name', 'news')
                    ->where('index_name', 'news_language_status_published_index')
                    ->exists();
                    
                if (!$indexes) {
                    $table->index(['language', 'status', 'published_at'], 'news_language_status_published_index');
                }
            });
        }

        // For impact_stats: is_active + sort_order
        if (Schema::hasTable('impact_stats')) {
            Schema::table('impact_stats', function (Blueprint $table) {
                $indexes = DB::table('information_schema.statistics')
                    ->where('table_schema', DB::getDatabaseName())
                    ->where('table_name', 'impact_stats')
                    ->where('index_name', 'impact_stats_active_sort_index')
                    ->exists();
                    
                if (!$indexes) {
                    $table->index(['is_active', 'sort_order'], 'impact_stats_active_sort_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropIndex('sliders_language_active_sort_index');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_language_status_published_index');
        });

        Schema::table('impact_stats', function (Blueprint $table) {
            $table->dropIndex('impact_stats_active_sort_index');
        });
    }
};
