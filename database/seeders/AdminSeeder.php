<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admins = [
            [
                'name'     => 'اياد سكيك',
                'email'    => 'eskaik@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'حسين الحجار',
                'email'    => 'hhajjar@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'فهيم المملوك',
                'email'    => 'fmamluk@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'احمد شعت',
                'email'    => 'ashaat@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'محمد ثابت',
                'email'    => 'mthabet@gedco.ps',
                'password' => Hash::make('admin123'),
            ],
        ];

        foreach ($admins as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                    'is_admin' => true,
                ]
            );

            if (!$user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        }
    }
}
