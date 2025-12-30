<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'user:create-test {email} {password} {name?}';
    protected $description = 'إنشاء مستخدم تجريبي للاختبار';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name') ?? 'Test User';

        // حذف المستخدم إذا كان موجوداً
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            if (!$this->confirm("المستخدم موجود بالفعل. هل تريد حذفه وإنشاء واحد جديد؟")) {
                $this->info('تم الإلغاء.');
                return;
            }
            $existingUser->delete();
            $this->info('تم حذف المستخدم القديم.');
        }

        // إنشاء المستخدم
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);

            $this->info("تم إنشاء المستخدم بنجاح!");
            $this->info("ID: {$user->id}");
            $this->info("Email: {$user->email}");
            $this->info("Name: {$user->name}");
            $this->info("is_admin: " . ($user->is_admin ? 'true' : 'false'));
            $this->info("Password: {$password}");

            // اختبار الباسورد
            $hashCheck = Hash::check($password, $user->password);
            $this->info("Hash::check: " . ($hashCheck ? '✓ صحيح' : '✗ خطأ'));

        } catch (\Exception $e) {
            $this->error("حدث خطأ: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

