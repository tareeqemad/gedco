<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // شبكة أمان: كل user عنده is_admin=true وما عنده role يتعين له admin
        if (Schema::hasColumn('users', 'is_admin')) {
            $usersWithoutRoles = DB::table('users')
                ->where('is_admin', true)
                ->whereNotIn('id', function ($query) {
                    $query->select('model_id')
                        ->from('model_has_roles')
                        ->where('model_type', User::class);
                })
                ->pluck('id');

            foreach ($usersWithoutRoles as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->assignRole('admin');
                }
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        // استرجاع is_admin لكل user عنده role
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->distinct()
            ->pluck('model_id')
            ->each(function ($userId) {
                DB::table('users')->where('id', $userId)->update(['is_admin' => true]);
            });
    }
};
