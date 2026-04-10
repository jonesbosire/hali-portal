<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@haliaccess.net'],
            [
                'name'              => 'HALI Secretariat',
                'password'          => Hash::make('HALIadmin2026!'),
                'role'              => 'super_admin',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Network Secretariat',
                'bio'               => 'The HALI Access Network Secretariat manages the partner portal and coordinates member organizations across Sub-Saharan Africa.',
            ]
        );

        User::firstOrCreate(
            ['email' => 'secretariat@haliaccess.net'],
            [
                'name'              => 'Network Coordinator',
                'password'          => Hash::make('HALIsecret2026!'),
                'role'              => 'secretariat',
                'status'            => 'active',
                'email_verified_at' => now(),
                'title'             => 'Network Coordinator',
            ]
        );
    }
}
