<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Kawan Tenang',
                'email' => 'admin@gmail.com',
                'role' => User::ROLE_ADMIN,
                'nim' => 'ADMIN-001',
                'prodi' => 'Administrasi',
                'is_active' => true,
            ],
            [
                'name' => 'Konselor Kawan Tenang',
                'email' => 'konselor@gmail.com',
                'role' => User::ROLE_KONSELOR,
                'nim' => 'KONSELOR-001',
                'prodi' => 'Bimbingan Konseling',
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    ...$account,
                    'password' => 'password',
                ],
            );

            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
