<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * create admin and sample users
     */
    public function run(): void
    {
        // ===== main admin =====
        $admin = User::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@system.com',
                'password' => Hash::make('admin123'),  // كلمة المرور: admin123
                'avatar_path' => 'avatars/admin-default.jpg',
                'role' => 2,  // Admin
            ]
        );

        // ===== admin user =====
        User::updateOrCreate(
            ['email' => 'superadmin@system.com'],
            [
                'name' => 'الإداري المساعد',
                'email' => 'superadmin@system.com',
                'password' => Hash::make('admin456'),
                'avatar_path' => 'avatars/superadmin-default.jpg',
                'role' => 2,  // Admin
            ]
        );

        // ===== users =====
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'password' => 'user123',
                'avatar_path' => 'avatars/user1.jpg',
                'role' => 1,  // User
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatma@example.com',
                'password' => 'user123',
                'avatar_path' => 'avatars/user2.jpg',
                'role' => 1,  // User
            ],
            [
                'name' => 'محمد حسن',
                'email' => 'mohamed@example.com',
                'password' => 'user123',
                'avatar_path' => 'avatars/user3.jpg',
                'role' => 1,  // User
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
