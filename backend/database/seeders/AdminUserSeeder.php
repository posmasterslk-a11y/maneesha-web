<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        DB::table('users')->insertOrIgnore([
            'name'       => 'Maneesha Admin',
            'email'      => 'admin@maneeshafashion.lk',
            'phone'      => '0771234567',
            'password'   => Hash::make('Admin@1234'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Admin user created:');
        $this->command->info('   Email   : admin@maneeshafashion.lk');
        $this->command->info('   Password: Admin@1234');
        $this->command->warn('   ⚠️  Please change the password after first login!');
    }
}
