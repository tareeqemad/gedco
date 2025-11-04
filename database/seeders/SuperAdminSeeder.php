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
                'password' => Hash::make('tareq123'),
            ],
            [
                'name'     => 'محمد الكيلاني',
                'email'    => 'mkilani@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
        ];

        foreach ($superAdmins as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                    'is_admin' => true,
                ]
            );

            if (!$user->hasRole('super-admin')) {
                $user->assignRole($superRole);
            }
        }
    }
}
