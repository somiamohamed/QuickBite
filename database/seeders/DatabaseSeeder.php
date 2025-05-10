<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // <-- أضف هذا السطر
use App\Models\User; // إذا كنت تستخدم User أيضًا

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // إنشاء بيانات تجريبية للمستخدمين
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // إنشاء بيانات تجريبية للمشرفين
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
    }
}