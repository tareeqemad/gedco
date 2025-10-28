<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class JobPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['jobs.view', 'jobs.create', 'jobs.edit', 'jobs.delete'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }
    }
}
