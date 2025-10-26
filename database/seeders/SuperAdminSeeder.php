<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        $superAdmins = [
            [
                'name'     => 'طارق البواب',
                'email'    => 'tareqelbawab94@gmail.com',
                'password' => Hash::make('tareq123'), // بدّلها فوراً
            ],
            [
                'name'     => 'فهيم المملوك',
                'email'    => 'admin2@example.com',
                'password' => Hash::make('password123'), // بدّلها
            ],
        ];

        foreach ($superAdmins as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                    'is_admin' => true, // لو عندك العمود القديم
                ]
            );

            if (!$user->hasRole('super-admin')) {
                $user->assignRole($superRole);
            }
        }
    }
}
